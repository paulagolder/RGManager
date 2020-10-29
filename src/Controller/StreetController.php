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


use App\Form\Type\StreetForm;
use App\Entity\Street;



;
//use Dompdf\Dompdf as Dompdf;
//use Dompdf\Options;


class StreetController extends AbstractController
{



  private $requestStack ;

  public function __construct( RequestStack $request_stack)
  {

    $this->requestStack = $request_stack;

  }


  public function Showall()
  {
    $streets = $this->getDoctrine()->getRepository("App:Street")->findAll();
    if (!$streets) {
      return $this->render('street/showall.html.twig', [ 'message' =>  'Streets not Found',]);
    }

    return $this->render('street/showall.html.twig',
    [

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
    'message' =>  '' ,
    'heading' => 'Problem Streets',
    'streets'=> $streets,
    'filter'=>$problemtype,
    ]);
  }

  public function StreetEditGroup($name)
  {
    $streets = $this->getDoctrine()->getRepository('App:Street')->findNamed($name);

    $rg = $streets[0]->getRoadgroupid();
    return $this->render('street/editgroup.html.twig', array(
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

  public function StreetEdit($rdid,$returnid)
  {
    $request = $this->requestStack->getCurrentRequest();
    if($rdid ==0)
      return $this->redirect("/ward/showall");
    $street = $this->getDoctrine()->getRepository('App:Street')->findOne($rdid);
    $streets = $this->getDoctrine()->getRepository('App:Street')->findNamed($street->getName());
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
        // $person->setContributor($user->getUsername());
        // $person->setUpdateDt($time);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($street);
        $entityManager->flush();
        $rdid = $street->getStreetId();
        return $this->redirect("/street/edit/".$rdid."/".$returnid);
      }
    }
    if ($returnid == "" or $returnid == Null or strlen($returnid) <2)
    {
      $returnpath = "/ward/showall";
    }
    else
      $returnpath = "/roadgroup/showone/$returnid";
    return $this->render('street/edit.html.twig', array(
      'form' => $form->createView(),
      'streetcount'=>$streetcount,
      'street'=>$street,
      'back'=>$returnpath,
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
    $swdid = $street->getSubwardId();
    $wdid = $street->getWardId();
    if($rgid)
      $back = "/roadgroup/showone/".$rgid;
    else  if($swdid)
      $back = "/subward/show/".$swdid;
    else if($wdid)
      $back =  "/ward/show/".$wdid;
    else
      $back =  "/ward/showall/";
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




  public function StreetRemove($stid)
  {
    if($stid>0)
    {
      $street = $this->getDoctrine()->getRepository('App:Street')->findOne($stid);
    }
    else
      return;
    $rgid = $street->getRoadgroupid();
    $street->setRoadgroupid(null);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($street);
    $entityManager->flush();
    return $this->redirect("/roadgroup/showone/".$rgid);
  }

  public function StreetAdd($rgid)
  {

    $stid = $_POST["selstreet"];
    if($stid>0)
    {
      $street = $this->getDoctrine()->getRepository('App:Street')->findOne($stid);
    }
    else
      return;
    $street->setRoadgroupid($rgid);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($street);
    $entityManager->flush();
    return $this->redirect("/roadgroup/showone/".$rgid);
  }
}
