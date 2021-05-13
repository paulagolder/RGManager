<?php
// src/Controller/PersonController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;
//use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\HttpFoundation\Response;

use App\Service\MapServer;
use App\Form\Type\StreetForm;
use App\Entity\Street;
use App\Entity\Roadgrouptostreet;




;
//use Dompdf\Dompdf as Dompdf;
//use Dompdf\Options;


class StreetController extends AbstractController
{

    private $requestStack ;
    private $rgyear ;

    public function __construct( RequestStack $request_stack,  MapServer $mapserver)
    {
        $this->requestStack = $request_stack;
        $mapserver->load();
        $this->rgyear  = $this->requestStack->getCurrentRequest()->cookies->get('rgyear');
    }

  public function Showall()
  {
    $streets = $this->getDoctrine()->getRepository("App:Street")->findAll();
    $this->fixPath($streets);
    if (!$streets)
    {
      return $this->render('street/showall.html.twig', [ 'message' =>  'Streets not Found',]);
    }

    return $this->render('street/showall.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'heading' => 'The streets',
    'streets'=> $streets,
    ]);

  }

  public function filters()
  {
    $message= null;
    if (isset($_POST['filter']))
    {
      $filter = ($_POST['filter']);
    }else
      $filter= "all";

    switch ($filter)
    {
      case  "duplicates":
        $streets = $this->getDoctrine()->getRepository("App:Street")->findDuplicates();
            $this->fixPath($streets);
        break;
      default :
        $streets = $this->getDoctrine()->getRepository("App:Street")->findAll();
            $this->fixPath($streets);
        break;
    }

    if (!$streets) {
      return $this->render('street/showall.html.twig', [ 'message' =>  'Streets not Found',]);
    }

    return $this->render('street/showduplicates.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  $message ,
    'heading' => 'The streets',
    'streets'=> $streets,
    ]);

  }


  public function ShowProblems($problemtype='')
  {
    $streets = $this->getDoctrine()->getRepository("App:Street")->findProblems($problemtype);
        $this->fixPath($streets);
    if (!$streets) {
      return $this->render('street/showproblems.html.twig', [ 'message' =>  'Streets not Found',]);
    }
    return $this->render('street/showproblems.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'heading' => 'Problem Streets',
    'streets'=> $streets,
    'filter'=>$problemtype,
    ]);
  }



  public function StreetViewGroup($name)
  {
   // $streets = $this->getDoctrine()->getRepository('App:Street')->findNamed($name,$this->rgyear );
   $streets = $this->getDoctrine()->getRepository('App:Street')->findAllbyName($name);
    $streets = $this->fixPath($streets);
    foreach($streets as $astreet)
    {
        $rg = $this->getDoctrine()->getRepository('App:Roadgrouptostreet')->getRoadgroup($astreet->getName(),$astreet->getPart(),$this->rgyear);
        dump($rg);
        $astreet->roadgroupid =$rg[0]["roadgroupid"];
    }
    return $this->render('street/viewgroup.html.twig', array(
      'rgyear'=>$this->rgyear,
      'message'=>"",
      'streets'=>$streets,
      'back'=>'/street/showall',
      ));
  }

  public function Search()
  {
    $searchfield = ($_POST['searchfield']);
    $streets = $this->getDoctrine()->getRepository('App:Street')->namesearch($searchfield);
        $this->fixPath($streets);
    return $this->render('street/showall.html.twig', array(
      'rgyear'=>$this->rgyear,
      'message'=>"",
      'streets'=>$streets,
      'back'=>'/street/showall',
      ));
  }

  public function StreetGroupOps()
  {

    if (isset($_POST['Merge']))
    {
      $streetlist = ($_POST['selectstreets']);
      $nstreet = new Street();
      foreach($streetlist as $seq)
      {
        $street = $this->getDoctrine()->getRepository('App:Street')->findOneBySeq($seq);
        $street->fixPath();
        $nstreet->merge($street);
      }
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($nstreet);
      $entityManager->flush();
      $gstreet = $nstreet->getName();
    }
    if (isset($_POST['Delete']))
    {
      $streetlist = ($_POST['selectstreets']);

      $entityManager = $this->getDoctrine()->getManager();
      foreach($streetlist as $seq)
      {

        $astreet = $this->getDoctrine()->getRepository('App:Street')->findOnebySeq($seq);
        $astreet->fixPath();
        $gstreet = $astreet->getName();
        $entityManager->remove($astreet);
      }
      $entityManager->flush();
    }
    return $this->redirect("/street/editgroup/".$gstreet);
  }

  public function StreetEdit($rdname,$rdpart)
  {
    if ($rdpart=="null" or $rdpart =="/" )  $rdpart = "";
    $request = $this->requestStack->getCurrentRequest();
    if(!$rdname) return $this->redirect("/rggroup/showall");
     $rg = $this->getDoctrine()->getRepository('App:Roadgrouptostreet')->getRoadgroup($rdname,$rdpart,$this->rgyear);
     if(count($rg)<1)
       $rgid= null;
     else
       $rgid = $rg[0]["roadgroupid"];
    $streets = $this->getDoctrine()->getRepository('App:Street')->findAllbyName($rdname);
        $this->fixPath($streets);
    $street = $this->getDoctrine()->getRepository('App:Street')->findOnebyName($rdname,$rdpart);
    $path = $street->getDecodedPath();
    dump($path);
    $tracks=$path;

    //$rgarry = $this->getDoctrine()->getRepository('App:Roadgrouptostreet')->getRoadgroup($rdname,$rdpart);
    if($street->getLatitude()<40)
    {
     if($rgid)
     {
       $aroadgroup =  $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
       $gd= $aroadgroup->getGeodata();
       $street->setLatitude($gd["midlat"]);
       $street->setLongitude($gd["midlong"]);
     }
    }
    $streetcount = sizeof($streets);
    if(! isset($street))
    {
      $street = new Street();
    }
    $form = $this->createForm(StreetForm::class, $street);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
       dump($street->getPath());
       $path = $street->getDecodedPath();
       dump($path);
$tracks=$path;
$newtracks =[];
foreach($tracks as $track)
{
  if(count($track->steps)>1)  $newtracks[] = $track;
}
$newpath = json_encode($newtracks);
dump($newtracks);
        $street->setPath($newpath);
        if($street->getPart() == null)$street->setPart("");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($street);
        $entityManager->flush();
        $rdid = $street->getStreetId();
        return $this->redirect('/street/edit/'.$rdid);
      }
    }

    return $this->render('street/edit.html.twig', array(
     'rgyear'=>$this->rgyear,
     'roadgroupid' =>$rgid,
      'form' => $form->createView(),
      'streetcount'=>$streetcount,
      'street'=>$street,
      'tracks'=>$tracks,
      'back'=>"/street/viewgroup/$rdname",
      ));
  }

  public function StreetDelete($stname,$stpart)
  {

      $astreet = $this->getDoctrine()->getRepository('App:Street')->findOnebyName($stname,$stpart);
         $astreet->fixPath();
      if($astreet==null)  return;
      $back =  "/rggroup/showall/";
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($satreet);
    $entityManager->flush();
    return $this->redirect($back);
  }

  public function StreetReplicate($stname,$stpart)
  {
    if($stname)
    {
      $astreet = $this->getDoctrine()->getRepository('App:Street')->findOnebyName($stname,$stpart);
         $astreet->fixPath();
      if(!$astreet) return $this->redirect("/street/showall");
    }
    else
      return $this->redirect("/street/editgroup/".$astreet->getName());
    $nstreet = new Street();
    $nstreet->merge($astreet);
    $nstreet->setSeq(0);
    $street->setPart($astreet->getPart() ."a");
    $street->setNote($astreet->getNote() ." Review Household Count");
    $nstreet->setPart($nstreet->getPart() ."b");
    $nstreet->setNote($astreet->getNote() ." Review Household Count");
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($astreet);
    $entityManager->persist($nstreet);
    $entityManager->flush();
    //now change roadgroupto street to match
    //
    return $this->redirect("/street/editgroup/".$astreet->getName());
  }




  public function StreetRemove($rgid,$stname,$stpart)
  {
   if ($stpart=="null")  $stpart = "";
     $this->getDoctrine()->getRepository('App:Roadgrouptostreet')->remove($rgid,$stname,$stpart,$this->rgyear);

    return $this->redirect("/roadgroup/showone/".$rgid);
  }

  public function StreetAdd($rgid)
  {

    $stid = $_POST["selstreet"];
    if($stid)
    {
      $astreet = $this->getDoctrine()->getRepository('App:Street')->findOnebyStreetId($stid);
         $astreet->fixPath();
    }
    else
      return;

  $this->getDoctrine()->getRepository('App:Roadgroup')->addStreet($astreet,$rgid,$this->rgyear);


    return $this->redirect("/roadgroup/showone/".$rgid);
  }

  function fixPath($streets)
  {
    foreach($streets as $street)
    {
      $street->setPath( $street->getpath());
    }
    return $streets;
  }
}
