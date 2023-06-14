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
    dump($pds);

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
    /*  foreach ($rglist as $rg)
     {                 *
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
  $entityManager = $this->getDoctrine()->getManager();
  $entityManager->persist($apollingdistrict);
  $entityManager->flush();*/
    $back =  "/pollingdistrict/showall/";
    // extra rgs?
    $totalhouseholds =0;
    $roadgroups = $rglist;

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
    $seat =  $this->getDoctrine()->getRepository("App:Pollingdistrict")->findSmallestSeat($pdid);
    $seats =  $this->getDoctrine()->getRepository("App:Pollingdistrict")->findAllSeats($pdid);
    $i=0;
        dump($seats);
    while(!$seats[$i]["kml"] )  $i=$i+1;
    $seat = $seats[$i];
     $link="/seat/showpds/LDC/".$seat["seatid"];
    dump($seat);
    if($seat->getGeodata_obj()) $geodata = new Geodata($seat["geodata"]);
    else $geodata = new $geodata();
    foreach ($streets as $street)
    {
      if(strlen($street->getPath()) > 40)
      {
        $street->{"color"} = "black";

      }else
        $street->{"color"} = "red";
    }
    dump($streets);
    $link=null;
/*    if(!$geodata->isGeodata())
      if($seat)
      {
        $geodata=  $geodata->makeGeodata($seat["geodata"]);
        $link="/seat/showpds/LDC/".$seat["seatid"];
      }
      */
      dump($geodata);
    //   if($seat) $geodata= $seat->getGeodata_obj();

  /*  $apollingdistrict->setGeodata($geodata);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($apollingdistrict);
    $entityManager->flush();*/
    $back =  "/pollingdistrict/showall/";

    // return $this->render('pollingdistrict/showstreets.html.twig',
    return $this->render('pollingdistrict/editstreets.html.twig',
                         [
                         'rgyear'=>$this->rgyear,
                         'message' =>  '' ,
                         'pollingdistrict'=>$apollingdistrict,
                         'streets' => $streets ,
                         'seats'=>$seats,
                         'showseat'=> $link,
                         'newstreet'=> "/pollingdistrict/newstreet/LDC/".$pdid,
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
      $seatid = $this->getDoctrine()->getRepository("App:Pollingdistrict")->findSeat($pdid,$this->rgyear,"district");
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
        if($apd->getKML())
        {
          $geodata =  $this->mapserver->scanRoute($seatid."/".$apd->getKML());
        }
        else
          $geodata= new Geodata();

        $apd->setGeodata($geodata);
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
                                                                 'back'=>'/seat/showpds/LDC/'.$seatid,
    ));
  }


  public function Update($pdid)
  {

    if($pdid)
    {
      $apd = $this->getDoctrine()->getRepository("App:Pollingdistrict")->findOne($pdid);
      dump($apd);
    }
    $geodata = new Geodata;
    $streets =  $this->getDoctrine()->getRepository("App:Street")->findAllbyPd($pdid);
    foreach ($streets as $street)
    {
      $astreet = $this->getDoctrine()->getRepository("App:Street")->findOnebySeq($street->getSeq());

      $geodata->mergeGeodata_obj($astreet->getGeodata_obj());
    }

    $apd->setGeodata($geodata);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($apd);
    $entityManager->flush();

    return $this->redirect("/pollingdistrict/show/".$pdid);

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



  public function newstreet($dtid,$pdid)
  {

    $astreet = new Street();
    $astreet->setPath("");
    dump($pdid);
    dump($dtid);
    $apd = $this->getDoctrine()->getRepository("App:Pollingdistrict")->findOne($pdid);
    $astreet->setGeodata($apd->getGeodata());
    $astreet->setUpdated(null);
    $astreet->setName("NEW STREET");
    $astreet->setDistrictId($dtid);
    $astreet->setPdId($pdid);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($astreet);
    $entityManager->flush();
    $pid = $astreet->getStreetId();
    dump($astreet);
    $seq= $astreet->getSeq();

    return $this->redirect("/pollingdistrict/showstreets/".$pdid);
  }

}
