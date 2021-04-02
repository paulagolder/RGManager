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
        break;
      default :
        $streets = $this->getDoctrine()->getRepository("App:Street")->findAll();
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
    $streets = $this->getDoctrine()->getRepository('App:Street')->findNamed($name,$this->rgyear );
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

    $streets = $this->getDoctrine()->getRepository('App:Street')->findAllbyName($rdname);
    $street = $this->getDoctrine()->getRepository('App:Street')->findOnebyName($rdname,$rdpart);
    //$rgarry = $this->getDoctrine()->getRepository('App:Roadgrouptostreet')->getRoadgroup($rdname,$rdpart);
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
        if($street->getPart() == null)$street->setPart("");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($street);
        $entityManager->flush();
        $rdid = $street->getStreetId();
        return $this->redirect('/street/viewgroup/'.$rdname);
      }
    }

    return $this->render('street/edit.html.twig', array(
     'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'streetcount'=>$streetcount,
      'street'=>$street,
      'back'=>"/street/viewgroup/$rdname",
      ));
  }

  public function StreetDelete($stname,$stpart)
  {

      $street = $this->getDoctrine()->getRepository('App:Street')->findOnebyName($stname,$stpart);
      if($street==null)  return;
      $back =  "/rggroup/showall/";
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($street);
    $entityManager->flush();
    return $this->redirect($back);
  }

  public function StreetReplicate($stname,$stpart)
  {
    if($stname)
    {
      $street = $this->getDoctrine()->getRepository('App:Street')->findOnebyName($stname,$stpart);
      if(!$street) return $this->redirect("/street/showall");
    }
    else
      return $this->redirect("/street/editgroup/".$street->getName());
    $nstreet = new Street();
    $nstreet->merge($street);
    $nstreet->setSeq(0);
    $street->setPart($street->getPart() ."a");
    $street->setNote($street->getNote() ." Review Household Count");
    $nstreet->setPart($nstreet->getPart() ."b");
    $nstreet->setNote($street->getNote() ." Review Household Count");
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($street);
    $entityManager->persist($nstreet);
    $entityManager->flush();
    //now change roadgroupto street to match
    //
    return $this->redirect("/street/editgroup/".$street->getName());
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
    }
    else
      return;

    $street = $this->getDoctrine()->getRepository('App:Roadgroup')->addStreet($astreet,$rgid,$this->rgyear);

    return $this->redirect("/roadgroup/showone/".$rgid);
  }
}
