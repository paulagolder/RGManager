<?php
// src/Controller/PoliingDistrictController.php
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

//use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\HttpFoundation\Response;

use App\Service\FileUploader;
use App\Form\Type\PDForm;
use App\Form\Type\RoadgroupForm;
use App\Form\Type\StreetForm;
use App\Entity\Roadgroup;
use App\Entity\Street;
use App\Entity\Geodata;
use App\Entity\Pollingdistrict;
use App\Service\MapServer;


;
//use Dompdf\Dompdf as Dompdf;
//use Dompdf\Options;


class PollingDistrictController extends AbstractController
{



    private $requestStack;
    private $mapserver;
    private $rgyear;

    public function __construct( RequestStack $request_stack, MapServer $mapserver)
    {
        $this->requestStack = $request_stack;
        $this->mapserver = $mapserver;
        $mapserver->load();
         $this->rgyear  = $this->requestStack->getCurrentRequest()->cookies->get('rgyear');
    }

  public function Showall()
  {
    $pds = $this->getDoctrine()->getRepository("App:Pollingdistrict")->findAllbyYear($this->rgyear);
    if (!$pds) {
      return $this->render('pollingdistrict/showall.html.twig', [ 'message' =>  'polling districts not Found',]);
    }

    return $this->render('pollingdistrict/showall.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'heading' => 'Polling Districts',
    'pds'=> $pds,
    'back'=>"/",
    ]);
  }


  public function Showone($pdid)
  {

    $rglist = $this->getDoctrine()->getRepository("App:Roadgroup")->findAllinPollingDistrict($pdid,$this->rgyear);
    $apollingdistrict = $this->getDoctrine()->getRepository("App:Pollingdistrict")->findOne($pdid);
    dump($apollingdistrict);
    $roadgroups = [];
    $geodata = new Geodata;
    foreach ($rglist as $rg)
    {
      $aroadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rg["roadgroupid"],$this->rgyear);
       $geodata->mergeGeodata_obj($aroadgroup->getGeodata_obj());
      $kml = $aroadgroup->getKML();
      if(!$this->mapserver->ismap($kml))
      {
        //$aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupId(),$this->rgyear));
      }
      $aroadgroup->setHouseholds(  $count = $this->getDoctrine()->getRepository("App:Roadgroup")->countHouseholds($aroadgroup->getRoadgroupId(), $this->rgyear));
      $roadgroups[] = $aroadgroup;
    }
    $apollingdistrict->geodata =$geodata;
    $back =  "/pollingdistrict/showall/";
    // extra rgs?
    $totalhouseholds =0;

    return $this->render('pollingdistrict/showpds.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'pollingdistrict'=>$apollingdistrict,
    'total'=>$totalhouseholds,
    'roadgroups' => $roadgroups ,
    'back'=>$back,
    ]);

  }


  public function Showstreets($pdid)
  {
    $apollingdistrict = $this->getDoctrine()->getRepository("App:Pollingdistrict")->findOne($pdid);
    $geodata = new Geodata;
     $streets =  $this->getDoctrine()->getRepository("App:Street")->findAllbyPd($pdid);
    foreach ($streets as $street)
    {
      $astreet = $this->getDoctrine()->getRepository("App:Street")->findOnebySeq($street->getSeq());


    }
   // $apollingdistrict->{"geodata"} =$geodata;
    $back =  "/pollingdistrict/showall/";

    return $this->render('pollingdistrict/showstreets.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'pollingdistrict'=>$apollingdistrict,
    'streets' => $streets ,
    'back'=>$back,
    ]);

  }


  public function Showoneb($rgid)
  {

    $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    $swdid = $roadgroup->getRgsubgroupid();
    $wdid = $roadgroup->getRggroupid();
    $stlist = [];
    $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid);
       $totalhouseholds = 0;
    foreach($streets as $astreet)
    {
        $stlist[]= $astreet->getStreetId();
        $totalhouseholds += $astreet->getHouseholds();
    }
    if($swdid)
       $back = "/rgsubgroup/show/".$swdid;
    else if($wdid)
       $back =  "/rggroup/show/".$wdid;
    else
       $back =  "/rggroup/showall/";
    $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets();


    return $this->render('roadgroup/showone.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'seat'=> null,
     'total'=>$totalhouseholds,
    'roadgroup' => $roadgroup ,
    'streets'=> $streets,
    'sparestreets'=>$extrastreets,
    'back'=>$back,
    ]);

  }

    public function Edit($pdid)
    {
        $request = $this->requestStack->getCurrentRequest();
        if($pdid)
        {
              $apd = $this->getDoctrine()->getRepository("App:Pollingdistrict")->findOne($pdid);
              dump($apd);
        }
        if(! isset($apd))
        {
            $apd= new pollingdistrict();
        }
        $form = $this->createForm(PDForm::class, $apd);
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                //$geodata =  $this->mapserver->loadRoute("districts/",$adistrict->getKML());
                //$adistrict->setGeodata($geodata);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($apd);
                $entityManager->flush();
               // $dtid = $adistrict->getDistrictid();
                return $this->redirect("/pollingdistrict/edit/".$pdid);
            }
        }
        return $this->render('pollingdistrict/edit.html.twig', array(
           'rgyear' => $this->rgyear,
            'form' => $form->createView(),
            'pd'=>$apd,
            'returnlink'=>'/',
            ));
    }


  public function EditRoadgroup($rgid)
  {
    $request = $this->requestStack->getCurrentRequest();
    if($rgid !== "new")
    {
      $roadgroup = $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid,$this->rgyear);
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

  public function NewPD($wdid, $swdid)
  {
    $request = $this->requestStack->getCurrentRequest();

    $roadgroup  = new Roadgroup ();
    $roadgroup->setWardid($wdid);
    $roadgroup->setSubwardid($swdid);
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



  public function Delete($pdid)
  {

    $roadgroup = $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid,$this->rgyear);
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
