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


use Symfony\Component\HttpFoundation\Response;
use App\Service\MapServer;
use App\Service\FileUploader;
use App\Form\Type\RoadgroupForm;
use App\Form\Type\StreetForm;
use App\Entity\Roadgroup;
use App\Entity\Street;



;
//use Dompdf\Dompdf as Dompdf;
//use Dompdf\Options;


class RoadgroupController extends AbstractController
{



    private $requestStack ;
    private $rgyear ;
    private $mapserver;

    public function __construct( RequestStack $request_stack,  MapServer $mapserver)
    {
        $this->requestStack = $request_stack;
        $this->mapserver = $mapserver;
        $this->mapserver->load();
        $this->rgyear  = $this->requestStack->getCurrentRequest()->cookies->get('rgyear');
    }


  public function Showall()
  {
    $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findAll();
    if (!$roadgroups) {
      return $this->render('roadgroup/showall.html.twig', [ 'message' =>  'roadgroups not Found',]);
    }
    $streets = array();
    $streetcount = $this->getDoctrine()->getRepository("App:Roadgrouptostreet")->countStreets($this->rgyear);
    foreach($streetcount as $street)
    {
      $streets[$street["roadgroupid"]] = $street["nos"];
    }
       $rgpds = $this->getDoctrine()->getRepository("App:Roadgrouptostreet")->countPDs($this->rgyear);


    foreach ( $roadgroups as $key=>$roadgroup)
    {

      if(array_key_exists($roadgroup->getRoadgroupId(),$streets))
      {
        $roadgroup->nos = $streets[$roadgroup->getRoadgroupId()];
      }
      else
         $roadgroup->nos=0;

      if(array_key_exists($roadgroup->getRoadgroupId(),$rgpds))
      {
        $roadgroup->pds = $rgpds[$roadgroup->getRoadgroupId()];
      }
      else
         $roadgroup->pds=0;

      $roadgroups[$key] = $roadgroup;
    }

    return $this->render('roadgroup/showall.html.twig',
    [
      'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'heading' => 'The Road Groups',
    'roadgroups'=> $roadgroups,
    'back'=>"/",
    ]);

  }

  public function xShowpds()
  {
    $rgpds = $this->getDoctrine()->getRepository("App:Roadgroup")->findPDs();
    if (!$rgpds) {
      return $this->render('roadgroup/showall.html.twig', [ 'message' =>  'roadgroups not Found',]);
    }
    $pds = array();
    foreach($rgpds as $rgpd)
    {
      $pd = $rgpd["pd"];
      $rg = $rgpd["rg"];

      if( ! array_key_exists ( $pd , $pds) )
     {
        $pds[$pd] = array();
      }
      if( !in_array ( $rg , $pds[$pd]) )
      {
         $pds[$pd][] = ($rg);
      }
    }

    return $this->render('roadgroup/showpds.html.twig',
    [
      'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'heading' => 'The Road Groups',
    'pds'=> $pds,
    'back'=>"/",
    ]);

  }


  public function Showone($rgid)
  {

    $seats= null;
    $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid);

    if(!$this->mapserver->ismap($roadgroup->getKML()))
    {
           $roadgroup->setKML($this->mapserver->findmap($roadgroup->getRoadgroupid(),$this->rgyear));

    }
    $swdid = $roadgroup->getRgsubgroupid();
    $wdid = $roadgroup->getRggroupid();
    $stlist = [];
    $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid,$this->rgyear);
    $totalhouseholds = 0;
    foreach($streets as $astreet)
    {
        $stlist[]= $astreet["name"];
        $totalhouseholds += $astreet["households"];
    }
    if($swdid)
       $back = "/rgsubgroup/show/".$swdid;
    else if($wdid)
       $back =  "/rggroup/show/".$wdid;
    else
       $back =  "/rggroup/showall/";
    $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets($this->rgyear);

    return $this->render('roadgroup/showone.html.twig',
    [
      'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'seats'=>$seats,
    'total'=>$totalhouseholds,
    'roadgroup' => $roadgroup ,
    'streets'=> $streets,
    'sparestreets'=>$extrastreets,
    'year'=>$this->rgyear,
    'back'=>$back,
    ]);

  }



  public function Edit($rgid)
  {
    $request = $this->requestStack->getCurrentRequest();
    if($rgid !== "new")
    {
      $roadgroup = $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid);
       $swdid = $roadgroup->getRgsubgroupid();
       $wdid = $roadgroup->getRggroupid();
    }
    if(! isset($roadgroup ))
    {
      $roadgroup  = new Roadgroup ();
    }
    $form = $this->createForm(RoadgroupForm::class, $roadgroup );
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
       $swdid = $roadgroup->getRgsubgroupid();
       $wdid = $roadgroup->getRggroupid();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($roadgroup );
        $entityManager->flush();
        $rgid = $roadgroup->getRoadgroupId();
        if($swdid)
           return $this->redirect("/rgsubgroup/show/".$swdid);
        else if($wdid)
           return $this->redirect("/rggroup/show/".$wdid);
         else
            return   $this->redirect("/");
      }
    }

    if($swdid)
           $back = "/rgsubgroup/show/".$swdid;
        else if($wdid)
            $back =  "/rggroup/show/".$wdid;
         else
             $back =  "/";
    return $this->render('roadgroup/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'roadgroup'=>$roadgroup,
      'back'=>$back,
      ));
  }

  public function NewRG($wdid, $swdid)
  {
    $request = $this->requestStack->getCurrentRequest();

    $roadgroup  = new Roadgroup ();
    $roadgroup->setRggroupid($wdid);
    $roadgroup->setRgsubgroupid($swdid);
    $form = $this->createForm(RoadgroupForm::class, $roadgroup );
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $wdid = $roadgroup->getRggroupid();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($roadgroup );
        $entityManager->flush();
        $rdid = $roadgroup->getRoadgroupId();
        if($wdid == "")
          return $this->redirect("/roadgroup/showall");
        else
          return $this->redirect("/rggroup/show/".$wdid);
      }
    }

    return $this->render('roadgroup/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'roadgroup'=>$roadgroup,
      'back'=>'/roadgroup/showall/',
      ));
  }

  public function StreetEdit($pid)
  {
    $request = $this->requestStack->getCurrentRequest();
    //$user = $this->getUser();
    // $time = new \DateTime();
    if($pid>0)
    {
      $street = $this->getDoctrine()->getRepository('App:Street')->findOne($pid);
    }
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
        $pid = $street->getStreetId();
        return $this->redirect("/street/edit/".$pid);
      }
    }

    return $this->render('street/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'street'=>$street,
      'returnlink'=>'/street/problems',
      ));
  }

  public function newstreet($rgid)
  {
    $request = $this->requestStack->getCurrentRequest();
    $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid);
    $astreet = new Street();
    $astreet->setRoadgroupId($rgid);
    $astreet->setSubwardId($roadgroup->getRgsubgroupid());
    $astreet->setWardId($roadgroup->getRggroupid());
    $astreet->setRoadgroupId($rgid);
    $form = $this->createForm(StreetForm::class, $astreet);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        // $person->setContributor($user->getUsername());
        // $person->setUpdateDt($time);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($astreet);
        $entityManager->flush();
        $pid = $astreet->getStreetId();
        return $this->redirect("/roadgroup/showone/".$rgid);
      }
    }

    return $this->render('street/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'street'=>$astreet,
      'back'=>'/roadgroup/showone/'.$rgid,
      ));
  }

  public function Delete($rgid)
  {

    $roadgroup = $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid);
    $swdid = $roadgroup->getRgsubgroupid();
       $wdid = $roadgroup->getRggroupid();
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($roadgroup);
    $entityManager->flush();
    if($swdid)
           $back = "/rgsubgroup/show/".$swdid;
        else if($wdid)
            $back =  "/rggroup/show/".$wdid;
         else
             $back =  "/rggroup/showall/";
    return $this->redirect($back);

  }





}
