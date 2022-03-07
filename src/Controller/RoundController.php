<?php

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
use Symfony\Component\Asset\Packages;

use Symfony\Component\HttpFoundation\Response;
//use Dompdf\Dompdf;


use App\Entity\Delivery;
use App\Entity\Round;
use App\Entity\Rggroup;
use App\Entity\Rgsubgroup;
use App\Form\Type\DeliveryForm;
use App\Entity\RoundtoRoadgroup;
//use App\Entity\DeliverytoRoadgroup;

use App\Service\MapServer;
use App\Service\TreeServer;



class RoundController extends AbstractController
{

  private $A4w = 210;
  private $A4h = 297;

  private $requestStack;
  private $mapserver;
  private $treeserver;
  private $rgyear ;

  public function __construct( RequestStack $request_stack, MapServer $mapserver, TreeServer $treeserver )
  {
    $this->requestStack = $request_stack;
    $this->mapserver = $mapserver;
    $this->treeserver = $treeserver;
    $mapserver->load();
    $this->rgyear  = $this->requestStack->getCurrentRequest()->cookies->get('rgyear');
  }






  public function scheduleround($dvyid, $rndid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $round= $this->getDoctrine()->getRepository("App:Round")->findOne($rndid);
    dump($round);
    $roadgroups =  $this->getDoctrine()->getRepository("App:Round")->getRoadgroups($rndid);
    dump($roadgroups);
    $round->{"countrgs"}=count($roadgroups);
    $rgstree = $this->spareRoadgroups($dvyid);
    dump($rgstree);
    $bounds =$this->mapserver->newbounds();
    $countrgs =0;
    foreach($roadgroups as $aroadgroup)
    {
      dump($aroadgroup);
      $countrgs++;
      if( array_key_exists( "geodata",$aroadgroup) && $aroadgroup["geodata"]!= null)
         $bounds = $this->mapserver->expandbounds($bounds, $this->mapserver->makebounds($aroadgroup["geodata"]));
    }
    if($round->getRgsubgroupId() != null)
    {
      $back = "/delivery/schedulesubgroup/".$dvyid."/".$round->getRggroupId()."/".$round->getRgsubgroupId();
    }
    else
      if($round->getRggroupId() != null)

        {
          $back = "/delivery/schedulegroup/".$dvyid."/".$round->getRggroupId();
        }
        else
        {
          $back = "/delivery/scheduledelivery/".$dvyid;
        }

    return $this->render('delivery/showround.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'delivery'=>$delivery,
    'around'=>$round,
    'roadgroups'=>$roadgroups,
    'bounds'=>$bounds,
    'rgstree'=>$rgstree,
    'back'=>$back,
    ]);
  }


  public function manageround($dvyid, $rndid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $district = $this->getDoctrine()->getRepository("App:District")->findOne($delivery->getDistrictId());
    $round= $this->getDoctrine()->getRepository("App:Round")->findOne($rndid);
    $dgs= $this->getDoctrine()->getRepository("App:RoundtoRoadgroup")->findRoadgroups($dvyid);
    return $this->render('round/manageround.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'delivery'=>$delivery,
    'dgs'=>$dgs,
    'round'=>$round,
    'back'=>"/round/manageround/",$dvyid
    ]);
  }

  public function manage($dvyid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $district = $this->getDoctrine()->getRepository("App:District")->findOne($delivery->getDistrictId());
    if (!$delivery)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    $rounds =  $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
    dump($rounds);
    $roundstree =     $this->treeserver->makeroundtree_object($rounds);
    dump($roundstree);
    return $this->render('round/managerounds.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'delivery'=>$delivery,
    'roundstree'=>$roundstree,
    //'dgs'=>$dgs,,
    'back'=>"/delivery/showcurrent"
    ]);
  }

  public function managegroup($dvyid, $grpid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $district = $this->getDoctrine()->getRepository("App:District")->findOne($delivery->getDistrictId());
    if (!$delivery)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    $rounds =  $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
    dump($rounds);
    $roundstree =     $this->treeserver->makeroundtree_object($rounds);
    dump($roundstree);
    $subroundstree = $roundstree[$grpid]["children"];
    dump($subroundstree);
    return $this->render('round/managegroup.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'delivery'=>$delivery,
    'rndgroup'=>   $roundstree[$grpid]["group"],
    'roundstree'=>$subroundstree,
    'back'=>"/delivery/showcurrent"
    ]);
  }

  public function managesubgroup($dvyid, $grpid, $sgrpid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $district = $this->getDoctrine()->getRepository("App:District")->findOne($delivery->getDistrictId());
    if (!$delivery)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    $rounds =  $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
    dump($rounds);
    $roundstree =     $this->treeserver->makeroundtree_object($rounds);
    dump($roundstree);
    $subroundstree = $roundstree[$grpid]["children"];
    $subsubroundstree = $subroundstree[$sgrpid]["children"];
    dump($roundstree);
    return $this->render('round/managesubgroup.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'delivery'=>$delivery,
    'rndgroup'=>   $roundstree[$grpid]["group"],
     'rndsubgroup' => $roundstree[$grpid]["children"][$sgrpid]["group"],
    'roundstree'=>$subsubroundstree,
    'bounds'=> null,
    'back'=>"/delivery/showcurrent"
    ]);
  }


  public function updateround($dvyid,$grpid,$sgrpid)
  {
    $rndid = $_POST['rndid'];
    $target = $_POST['target'];
    $completed = $_POST['completed'];
    $agent = $_POST['agent'];
    $entityManager = $this->getDoctrine()->getManager();

    foreach( $rndid as $key => $id )
    {
      $round= $this->getDoctrine()->getRepository("App:Round")->findOne($rndid[$key]);
       $round->setTarget($target[$key]);
       $round->setCompleted($completed[$key]);
       $round->setAgent($agent[$key]);
       $entityManager->persist($round);
       $entityManager->flush();
    }
    return $this->managesubgroup($dvyid,$grpid,$sgrpid);

  }


  public function updatefromroadgroups($dvyid,$rndid)
  {
    $round= $this->getDoctrine()->getRepository("App:Round")->findOne($rndid);
    $rgs =$this->getDoctrine()->getRepository("App:RoundtoRoadgroup")->findRoadgroups($rndid,$this->rgyear);

    $nrg=0;
    $kmldom =   $this->mapserver->makekmldoc($round->getRoundId());
    $totalhouseholds = 0;
    $totalelectors = 0;
    $totaldist = 0;
    $totalstreets = 0;

    $dist = 0;
    $minlat=360;
    $minlong =360;
    $maxlat=-360;
    $maxlong=-360;

    foreach($rgs as $rtrg)
    {
      $rg = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rtrg->getRoadgroupId(),$this->rgyear);
      $nrg++;
      $rgkml= $rg->getKML();
      $kmldom = $this->mapserver->MergeRoute($kmldom,$rgkml);
      $totalhouseholds += $rg->getHouseholds();;
      $totalelectors += $rg->getElectors();
      $stgd = $rg->getGeodata();
      if($stgd != null)
      {
      $totaldist += $stgd["dist"];
      $aminlat = floatval($stgd["minlat"]);
      $aminlong = floatval($stgd["minlong"]);
      $amaxlat = floatval($stgd["maxlat"]);
      $amaxlong = floatval($stgd["maxlong"]);
      if($aminlong> -360)
      {
        if($minlat > $aminlat && $aminlat != 0)$minlat = $aminlat;
        if($minlong > $aminlong && $aminlong != 0)$minlong = $aminlong;
        if($maxlong <  $amaxlong && $amaxlong != 0)$maxlong = $amaxlong;
        if($maxlat <  $amaxlat  &&  $amaxlat != 0)$maxlat = $amaxlat;
      }
      }

    }
    $geodata = array();
    $geodata["dist"]=$totaldist;
    $geodata["maxlat"]=$maxlat;
    $geodata["midlat"]=($maxlat+$minlat)/2;
    $geodata["minlat"]=$minlat;
    $geodata["maxlong"]=$maxlong;
    $geodata["midlong"]=($minlong+$maxlong)/2;
    $geodata["minlong"]=$minlong;
    $time = new \DateTime();
    $round->setUpdated($time);
    $round->setGeodata($geodata);
    $round->setDistance($totaldist);
    $round->setHouseholds($totalhouseholds);
   // $round->setElectors($totalelectors);
    $round->setRoadgroups($nrg);
    $rndkml = $this->exportKML($rndid);
    dump($kmldom);
    dump($rndkml);
    $round->setKML($rndkml);
    $round->setGeodata($geodata);
    $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($round);
      $entityManager->flush();
      $rndgroupid =$round->getRggroupId();
      $rndsubgroupid =$round->getRgsubgroupId();

      return $this->redirect("/delivery/scheduleround/$dvyid/$rndid");


  }

  public function newkml($rndid)
  {
    $roadgroups = $this->getDoctrine()->getRepository("App:RoundtoRoadgroup")->findRoadgroups($rndid);
    dump($roadgroups);
    if(!$roadgroups)
    {
      return $this->render('roadgroup/showone.html.twig',  [    'rgyear'=>$this->rgyear,'message' =>  'No roadgroups not Found',]);
    }
    $newkml = "";
    foreach($roadgroups as $roadgroup)
    {
    $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($roadgroup->getRoadgroupId(),$this->rgyear);

    $totaldist =0;
    foreach($streets as $astreet)
    {
      dump($astreet->getName());
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
    }
    return $newkml;
  }


  public function deleteround($dvyid,$rndid)
  {

    $around=  $this->getDoctrine()->getRepository("App:Round")->delete($rndid);
    $done = $this->getDoctrine()->getRepository("App:RoundtoRoadgroup")->removelinks($rndid);
    return $this->redirect( "/delivery/scheduledelivery/".$dvyid);
  }

  public function exportkml($rndid)
  {
    $year=$this->rgyear;
    $round= $this->getDoctrine()->getRepository("App:Round")->findOne($rndid,$this->rgyear);
    if($round->getLabel())
    {
      $kmlname = $round->getLabel()."_".$year.".kml";
    }
    else
       $kmlname = "ROUND_".$rndid."_".$year.".kml";
    $file = "maps/rounds/".$kmlname;
    $xmlout = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xmlout .= "<kml xmlns=\"http://www.opengis.net/kml/2.2\">\n";
    $xmlout .=  "<Document>\n";
    $xmlout .=  " <name>".$kmlname."</name>\n";
    $xmlout .=  "<Style id=\"blueLine\">\n";
    $xmlout .=  "  <LineStyle>\n";
    $xmlout .=  "    <color>7fff0000</color>\n";
    $xmlout .=  "    <width>4</width>\n";
    $xmlout .=  "  </LineStyle>\n";
    $xmlout .=  "</Style>\n";

    $xmlout .=  $this->newkml($rndid);

    $xmlout .=  "</Document>\n";
    $xmlout .=  "</kml>";
    dump($xmlout);
    $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
    fwrite($handle, $xmlout) or die ("ERROR: Cannot write the file.");
    fclose($handle);
    $this->addFlash('notice 2','kml file saved'.$file );
    $round->setKml($kmlname);
    $time = new \DateTime();
    $round->setUpdated($time);

    $round->setGeodata(null);
    $round->setDistance(-1);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($round);
    $entityManager->flush();
   // $this->updateGeodata($rndid);
    return $kmlname;
  }


  function updateGeodata($rndid)
  {
    $year=$this->rgyear;
    $round= $this->getDoctrine()->getRepository("App:Round")->findOne($rndid);
    $geodata = $this->makeGeodata($rndid);
    $round->setGeodata($geodata);
    $round->setDistance($geodata["dist"]);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($round);
    $entityManager->flush();
  }

  public function xmakeGeodata($rgid)
  {
    $roadgroup= $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    $streets =  $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid,$this->rgyear);
    $totalhouseholds = 0;
    $totalelectors = 0;
    $totaldist = 0;
    $totalstreets = 0;

    $dist = 0;
    $minlat=360;
    $minlong =360;
    $maxlat=-360;
    $maxlong=-360;
    foreach($streets as $astreet)
    {
      $totalhouseholds += $astreet->getHouseholds();
      $totalelectors += $astreet->getElectors();

      $totalstreets ++;
      $astreet->makeGeodata();
      $stgd = $astreet->getGeodata();
      $totaldist += $stgd["dist"];
      $aminlat = floatval($stgd["minlat"]);
      $aminlong = floatval($stgd["minlong"]);
      $amaxlat = floatval($stgd["maxlat"]);
      $amaxlong = floatval($stgd["maxlong"]);
      if($aminlong> -360)
      {
        if($minlat > $aminlat && $aminlat != 0)$minlat = $aminlat;
        if($minlong > $aminlong && $aminlong != 0)$minlong = $aminlong;
        if($maxlong <  $amaxlong && $amaxlong != 0)$maxlong = $amaxlong;
        if($maxlat <  $amaxlat  &&  $amaxlat != 0)$maxlat = $amaxlat;
      }
    }

    $geodata = array();
    $geodata["dist"]=$totaldist;
    $geodata["maxlat"]=$maxlat;
    $geodata["midlat"]=($maxlat+$minlat)/2;
    $geodata["minlat"]=$minlat;
    $geodata["maxlong"]=$maxlong;
    $geodata["midlong"]=($minlong+$maxlong)/2;
    $geodata["minlong"]=$minlong;
    $time = new \DateTime();
    $round->setUpdated($time);
    $roadgroup->setGeodata($geodata);
    $roadgroup->setDistance($totaldist);
    $roadgroup->setHouseholds($totalhouseholds);
    $roadgroup->setElectors($totalelectors);
    $roadgroup->setStreets($totalstreets);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($roadgroup);
    $entityManager->flush();
    return $geodata;
  }

}


