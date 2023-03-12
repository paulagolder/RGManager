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
use App\Entity\Geodata;



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
    $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findAllbyYear($this->rgyear);
    if (!$roadgroups)
    {
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
        $roadgroup->nos=-999;

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



  public function Showone($rgid)
  {
    $seats= null;
    $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    if(!$roadgroup)
    {
      return $this->render('roadgroup/showone.html.twig',  [    'rgyear'=>$this->rgyear,'message' =>  'No roadgroups not Found',]);
    }
    $currentkml = $roadgroup->getKML();
    $swdid = $roadgroup->getRgsubgroupid();
    $wdid = $roadgroup->getRggroupid();
    $stlist = [];
    $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid,$this->rgyear);
    $pds = '"';
    foreach($streets as $astreet)
    {
      if(!str_contains($pds, $astreet->getPdId()))
          $pds .=  $astreet->getPdId().',';

    }
    $pds .=')"';
    dump($pds);
    $pds = str_replace(",)","",$pds);
    dump($pds);
    $extrastreetsinpd = null;
    if(strlen($pds) >2)
       $extrastreetsinpd =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreetsInPd($pds,$this->rgyear);
    if($swdid)
      $back = "/rgsubgroup/show/".$swdid;
    else if($wdid)
      $back =  "/rggroup/show/".$wdid;
    else
      $back =  "/rggroup/showall/";
    $extrastreetsloose =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets($this->rgyear);
    $geodata= $roadgroup->getGeodata_obj();
    dump($roadgroup);
    dump($geodata);
    if($geodata->maxlat <-350)
    {
      $agroup = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($roadgroup->getRggroupid());
      $geodata = $agroup->getGeodata();
      $roadgroup->setGeodata($geodata);
    }
    $extrastreets = array_merge(  $extrastreetsinpd ,    $extrastreetsloose );

    return $this->render('roadgroup/showone.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'seats'=>$seats,
    'roadgroup' => $roadgroup ,
    'streets'=> $streets,
    'sparestreets'=>$extrastreets,
    'year'=>$this->rgyear,
    'back'=>$back,
    ]);

  }


  public function Showheat($rgid)
  {
    $seats= null;
    $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    if(!$roadgroup)
    {
      return $this->render('roadgroup/showone.html.twig',  [    'rgyear'=>$this->rgyear,'message' =>  'No roadgroups not Found',]);
    }
    $currentkml = $roadgroup->getKML();
    $swdid = $roadgroup->getRgsubgroupid();
    $wdid = $roadgroup->getRggroupid();
    $stlist = [];
    $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid,$this->rgyear);
    if($swdid)
      $back = "/rgsubgroup/show/".$swdid;
    else if($wdid)
      $back =  "/rggroup/show/".$wdid;
    else
      $back =  "/rggroup/showall/";
    $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets($this->rgyear);
    $geodata= $roadgroup->getGeodata();

    return $this->render('roadgroup/showone.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'seats'=>$seats,
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
    $roadgroup = $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid,$this->rgyear);
    if( isset($roadgroup))
    {
      $swdid = $roadgroup->getRgsubgroupid();
      $wdid = $roadgroup->getRggroupid();
    }

    if(! isset($roadgroup ))
    {
      //$roadgroup  = new Roadgroup ();
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
      //  $entityManager->merge($roadgroup);
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

  public function removefromsubgroup($rgid)
  {
    $roadgroup = $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid,$this->rgyear);
    $swdid = $roadgroup->getRGsubgroupid();
    $roadgroup->setRGsubgroupid(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($roadgroup );
        $entityManager->flush();
        return $this->redirect("/rgsubgroup/show/".$swdid);

  }

  public function NewRG($wdid, $swdid)
  {
    $request = $this->requestStack->getCurrentRequest();

    $roadgroup  = new Roadgroup ( $this->getDoctrine()->getManager());
    $roadgroup->setRggroupid($wdid);
    $roadgroup->setRgsubgroupid($swdid);
    $roadgroup->setYear($this->rgyear);
    $form = $this->createForm(RoadgroupForm::class, $roadgroup );
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $roadgroup->setUpdated(null);
        $wdid = $roadgroup->getRggroupid();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($roadgroup);
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

    if($pid>0)
    {
      $astreet = $this->getDoctrine()->getRepository('App:Street')->findOne($pid);
    }
    if(! isset($astreet))
    {
      $astreet = new Street();
    }
    $form = $this->createForm(StreetForm::class, $astreet);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $time = new \DateTime();
        $astreet->setUpdated($time);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($astreet);
        $entityManager->flush();
        $pid = $street->getStreetId();
        return $this->doUpdating($pid);

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
    $aroadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    $astreet = new Street();
    $gd= $aroadgroup->getGeodata();

    $form = $this->createForm(StreetForm::class, $astreet);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $astreet->setPath("");
        $astreet->setUpdated(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($astreet);
        $entityManager->flush();
        $pid = $astreet->getStreetId();
        $seq= $astreet->getSeq();
       // return $this->redirect("/roadgroup/showone/".$rgid);
        $rgst = new RoadgrouptoStreet();
        $rgst.setStreet($pid);
        $rgst.setStreetId($pid);
        $rgst.setRoadgroupId($rgid);
        return $this->doUpdating($rgid);

      }
    }

    return $this->render('street/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'street'=>$astreet,
      'roadgroupid'=>$rgid,
      'back'=>'/roadgroup/showone/'.$rgid,
      ));
  }

  public function Delete($rgid)
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




  public function newkml($rgid)
  {
    $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    if(!$roadgroup)
    {
      return "";
    }
    $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid,$this->rgyear);
    $newkml = "";
    foreach($streets as $astreet)
    {
      $newkml .= $astreet->makekml();
    }
    return $newkml;
  }



  public function exportkml($rgid)
  {
    $year=$this->rgyear;
    $roadgroup= $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    $kmlname = $rgid."_".$year.".kml";
    $file = "maps/roadgroups/".$kmlname;
    $xmlout = "";
    $xmlout .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xmlout .= "<kml xmlns=\"http://www.opengis.net/kml/2.2\">\n";
    $xmlout .=  "<Document>\n";
    $xmlout .=  " <name>".$kmlname."</name>\n";
    $xmlout .=  "<Style id=\"blueLine\">\n";
    $xmlout .=  "  <LineStyle>\n";
    $xmlout .=  "    <color>7fff0000</color>\n";
    $xmlout .=  "    <width>4</width>\n";
    $xmlout .=  "  </LineStyle>\n";
    $xmlout .=  "</Style>\n";

    $xmlout .=  $this->newkml($rgid);

    $xmlout .=  "</Document>\n";
    $xmlout .=  "</kml>\n";
    $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
    fwrite($handle, $xmlout) or die ("ERROR: Cannot write the file.");
    fclose($handle);
    $this->addFlash('notice 2','kml file saved'.$file );
    $roadgroup->setKml($kmlname);
    $time = new \DateTime();
    $roadgroup->setUpdated($time);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($roadgroup);
    $entityManager->flush();

  }

   public function updateRoadgroup($rgid)
   {
     $roadgroup= $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
     $this->makeGeodata($rgid);
     dump($roadgroup);
     if(!$roadgroup->getGeodata())
     {
       $rgsubgroupid = $roadgroup->getRgsubgroupid();
       $rgsubgroup = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findOne($rgsubgroupid );
       if(!$rgsubgroup->getGeodata())
       {
         $rggroupid = $rgsubgroup->getRggroupid();
         $rggroup = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($rggroupid );
       }

    }
     $this->exportKML($rgid);
     return $this->redirect('/roadgroup/showone/'.$rgid);
   }


   public function makeGeodata($rgid)
   {
     $roadgroup= $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
     $streets =  $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid,$this->rgyear);
     $totalhouseholds = 0;
     $totalelectors = 0;
     $totalstreets = 0;
     $totalsteps =0;
     $totplvw = 0;
     $totplvn =0;
     $geodata = new Geodata;
     $maxhh = 0;
     $name="??";
     foreach($streets as $astreet)
     {
       $sthh = $astreet->getHouseholds();
       if($sthh > $maxhh)
       {
         $maxhh= $sthh;
         $name = $astreet->getName();
       }
       $totalhouseholds += $astreet->getHouseholds();
       $totalelectors += $astreet->getElectors();
       $totalsteps += $astreet->getsteps() ;
       $totalstreets ++;
       $astreet->makeGeodata();
       $stgd = $astreet->getGeodata();
       $geodata->mergeGeodata_array($stgd);
       $totplvw += $astreet->getPLVW();
       $totplvn += $astreet->getPLVN();;
     }
      $geodata->steps =  $totalsteps;
      $geodata->streets = $totalstreets;
      $geodata->roadgroups =1;
     $time = new \DateTime();
     $roadgroup->setUpdated($time);
     $roadgroup->setGeodata($geodata);
     if(!str_ends_with($roadgroup->getName(),"."))
        $roadgroup->setName($name);
     $roadgroup->setHouseholds($totalhouseholds);
     $roadgroup->setElectors($totalelectors);
     $roadgroup->setStreets($totalstreets);
     $roadgroup->setPLVW($totplvw);
     $roadgroup->setPLVN($totplvn);

     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($roadgroup);
     $entityManager->flush();


   }


}
