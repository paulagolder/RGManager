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
    $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findAllbyYear($this->rgyear);
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
    /*   $foundkml = $this->mapserver->findmap($roadgroup->getRoadgroupid(),$this->rgyear);
   if(!$currentkml || strcmp($currentkml, $foundkml) !== 0)
    {
      $time = new \DateTime();
      $roadgroup->setUpdated(null);
      $roadgroup->setKml($foundkml);
      $roadgroup->setGeodata(null);
      $roadgroup->setDistance(-1);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($roadgroup);
      $entityManager->flush();

    }*/
    $swdid = $roadgroup->getRgsubgroupid();
    $wdid = $roadgroup->getRggroupid();
    $stlist = [];
    $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid,$this->rgyear);
    $totalhouseholds = 0;
    $totaldist = 0;
    $rgdate = $roadgroup->getUpdated();
    $needsupdating = false;
    $format = 'Y-m-d H:i:s';
    $rgdate = $roadgroup->getUpdated();
    foreach($streets as $astreet)
    {
      $stlist[]= $astreet->getName();
      $sdate =new \DateTime( $astreet->getUpdated());
      if ($sdate > $rgdate) {
        $needsupdating = true;
      }
      $totalhouseholds += $astreet->getHouseholds();
      $totaldist += $astreet->getDistance();
    }
    if($swdid)
      $back = "/rgsubgroup/show/".$swdid;
    else if($wdid)
      $back =  "/rggroup/show/".$wdid;
    else
      $back =  "/rggroup/showall/";
    $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets($this->rgyear);
    $geodata= $roadgroup->getGeodata();
    if($geodata === null)
    {
      if($roadgroup->getKML())
      {
      $geodata= $this->mapserver->loadRoute($roadgroup->getKML());
      $roadgroup->setGeodata($geodata);
      $roadgroup->setDistance($geodata["dist"]);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($roadgroup);
      $entityManager->flush();
      }
      else
      {
        $dist = 0;
        $minlat=360;
        $minlong =360;
        $maxlat=-360;
        $maxlong=-360;
        foreach($streets as $astreet)
        {
          $lat = floatval($astreet->getLatitude());
          $long = floatval($astreet->getLongitude());
          if($lat> 0)
          {
            if($minlat > $lat && $lat > 0)$minlat = $lat;
            if($minlong > $long && $long != 0)$minlong = $long;
            if($maxlong <  $long && $long != 0)$maxlong = $long;
            if($maxlat <  $lat  &&  $lat > 0)$maxlat = $lat;
          }
          $geodata = array();
          $geodata["dist"]=$dist;
          $geodata["maxlat"]=$maxlat;
          $geodata["midlat"]="".($maxlat+$minlat)/2;
          $geodata["minlat"]=$minlat;
          $geodata["maxlong"]=$maxlong;
          $geodata["midlong"]="".($minlong+$maxlong)/2;
          $geodata["minlong"]=$minlong;
          $time = new \DateTime();
          $roadgroup->setUpdated($time);
          $roadgroup->setGeodata($geodata);
          $roadgroup->setDistance($geodata["dist"]);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($roadgroup);
          $entityManager->flush();
        }
      }
    }
    return $this->render('roadgroup/showone.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'needsupdating'=>$needsupdating,
    'seats'=>$seats,
    'total'=>$totalhouseholds,
    'totaldist'=>$totaldist,
    'geodata'=>$geodata,
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

  public function NewRG($wdid, $swdid)
  {
    $request = $this->requestStack->getCurrentRequest();

    $roadgroup  = new Roadgroup ();
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
    $aroadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    $astreet = new Street();
    $gd= $aroadgroup->getGeodata();
    $astreet->setLatitude($gd["midlat"]);
    $astreet->setLongitude($gd["midlong"]);
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
        return $this->redirect("/roadgroup/showone/".$rgid);
        $rgst = new RoadgrouptoStreet();
        $rgst.setStreet($pid);
        $rgst.setRoadgroupId($rgid);

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


  public function UpdateHouseholds($rgid)
  {

    $this->getDoctrine()->getRepository('App:Roadgroup')->updateHouseholds($rgid,$this->rgyear);

    return $this->redirect($back);

  }

  public function newkml($rgid)
  {
    $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    if(!$roadgroup)
    {
      return $this->render('roadgroup/showone.html.twig',  [    'rgyear'=>$this->rgyear,'message' =>  'No roadgroups not Found',]);
    }

    $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid,$this->rgyear);
    $totalhouseholds = 0;
    $totaldist = 0;
    $newkml = "";
    foreach($streets as $astreet)
    {
      $path = $astreet->getDecodedpath();
      foreach($path as $branch)
      {
        if(count($branch->steps)>1)
        {

       // $geodata = $this->loadBranch($branch->steps);
       // $dist += $geodata["dist"];
        $newkml .=  "<Placemark>\n";
        $newkml .=  "  <name>".$astreet->getName()."</name>\n";
        $newkml .=  "  <styleUrl>#blueLine</styleUrl>\n";
        $newkml .=  "  <LineString>\n";
        $newkml .=  "	   <coordinates>\n";
        foreach($branch->steps as $step)
        {
          $newkml .="".$step[1].",".$step[0]."\n";
        }
        $newkml .=  "    </coordinates>\n";
        $newkml .=  "  </LineString>\n";
        $newkml .=  "</Placemark>\n";
      }
      }
      $totaldist += $astreet->getDistance();
    }

   // if(!$currentkml || strcmp($currentkml, $foundkml) !== 0)
    {
     // $roadgroup->setKml($foundkml);
    //  $roadgroup->setGeodata(null);
    //  $roadgroup->setDistance($totaldist);
    //  $entityManager = $this->getDoctrine()->getManager();
    //  $entityManager->persist($roadgroup);
   //   $entityManager->flush();
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
    $this->addFlash('notice','kml file saved'.$file );
    $roadgroup->setKml($kmlname);
    $time = new \DateTime();
    $roadgroup->setUpdated($time);
    $roadgroup->setGeodata(null);
    $roadgroup->setDistance(-1);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($roadgroup);
    $entityManager->flush();
    $this->updateGeodata($rgid);
    return $this->redirect("/roadgroup/showone/".$rgid);
  }


  function updateGeodata($rgid)
  {
    $year=$this->rgyear;
    $roadgroup= $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    dump($roadgroup->getGeodata());
    $geodata= $this->mapserver->loadRoute($roadgroup->getKML());
    dump($geodata);
    $roadgroup->setGeodata($geodata);
    $roadgroup->setDistance($geodata["dist"]);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($roadgroup);
    $entityManager->flush();
}


}
