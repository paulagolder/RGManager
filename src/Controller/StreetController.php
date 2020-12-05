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

  public function StreetEditGroup($name)
  {
    $streets = $this->getDoctrine()->getRepository('App:Street')->findNamed($name);
    return $this->render('street/editgroup.html.twig', array(
      'rgyear'=>$this->rgyear,
      'message'=>"",
      'streets'=>$streets,
      'back'=>'/street/showall',
      ));
  }


  public function StreetViewGroup($name)
  {
    $streets = $this->getDoctrine()->getRepository('App:Street')->findNamed($name,$this->rgyear);
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
      foreach($streetlist as $streetid)
      {
        $street = $this->getDoctrine()->getRepository('App:Street')->findOne($streetid);
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
      foreach($streetlist as $streetid)
      {
        $astreet = $this->getDoctrine()->getRepository('App:Street')->findOne($streetid);
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

    $streets = $this->getDoctrine()->getRepository('App:Street')->findNamed($rdname);
    $street = $this->getDoctrine()->getRepository('App:Street')->findOne($rdname,$rdpart);
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
      'back'=>'/street/viewgroup/'.$rdname,
      ));
  }

  public function StreetDelete($rdid)
  {
    if($rdid>0)
    {
      $street = $this->getDoctrine()->getRepository('App:Street')->findOne($rdid);
    }
    else
      return;
    $rgid = $street->getRoadgroupId();
    $swdid = $street->getRgsubgroupid();
    $wdid = $street->getRggroupid();
    if($rgid)
      $back = "/roadgroup/showone/".$rgid;
    else  if($swdid)
      $back = "/rgsubgroup/show/".$swdid;
    else if($wdid)
      $back =  "/rggroup/show/".$wdid;
    else
      $back =  "/rggroup/showall/";
    $roadgroupid = $street->getRoadgroupid();
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($street);
    $entityManager->flush();
    return $this->redirect($back);
  }

  public function StreetReplicate($stid)
  {
    if($stid>0)
    {
      $street = $this->getDoctrine()->getRepository('App:Street')->findOne($stid);
    }
    else
      return;
    $nstreet = new Street();
    $nstreet->merge($street);
    $street->setPart($street->getPart() ."a");
    $nstreet->setPart($nstreet->getPart() ."b");
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($street);
    $entityManager->persist($nstreet);
    $entityManager->flush();
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
