<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;


use Symfony\Component\HttpFoundation\Response;
//use Dompdf\Dompdf;

use App\Form\Type\RggroupForm;
use App\Form\Type\NewRgsubgroupForm;
use App\Form\Type\RgsubgroupForm;
use App\Entity\Rggroup;
use App\Entity\Rgsubgroup;
use App\Entity\Roadgroup;
use App\Entity\Geodata;
use App\Service\MapServer;
use App\Controller\Roadgroupcontroller;

//use App\Service\PDF;
//use Fpdf\Fpdf;
//use Dompdf\Exception;

//require('fpdf.php');

class RggroupController extends AbstractController
{

  private $A4w = 210;
  private $A4h = 297;

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


  public function showall()
  {
    $mappath= $this->container->get('parameter_bag')->get('mappath');
    $maproot = $this->container->get('parameter_bag')->get('maproot');
    $topmap = $this->container->get('parameter_bag')->get('topmap');
    $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
    if (!$rggroups)
    {
      return $this->render('rggroup/showone.html.twig', [  'rgyear'=>$this->rgyear, 'message' =>  'rggroups not Found',]);
    }
    $geodata = new Geodata();
     for($i=0; $i<count($rggroups); $i++)
    {
      $rggroup = $rggroups[$i];
      $rgs = $this->getDoctrine()->getRepository("App:Roadgroup")->findRoadgroupsinRGGroup($rggroup->getRggroupid(),$this->rgyear);
      $nrg = 0;
      $geodata->mergeGeodata_obj($rggroup->getGeodata_obj());

      for($j=0;$j<count($rgs);$j++)
      {
        $rgid = $rgs[$j]->getRoadgroupId();
        $aroadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
        $kml = $rgs[$j]->getKML();
        if($kml)
        {
          $mpath = $maproot."/roadgroups/".$kml;
          $mpath = str_replace("//","/",$mpath);
          if(file_exists($mpath))
          {
            $nrg++;
          }
        }

      }

      $rggroup->mapsfound= $nrg;
      $rggroup->roadgrouplist= $rgs;
      $rggroup->setRoadgroups(count($rgs));
      $rggroups[$i]=$rggroup;
    }

    $extraroadgroups =  $this->getDoctrine()->getRepository("App:Roadgroup")->findLooseRoadgroups();
    return $this->render('rggroup/showall.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'heading' => 'The rggroups',
    'topmap'=>$topmap,
    'geodata'=> $geodata,
    'rggroups' => $rggroups,
    'roadgroups' => $extraroadgroups,
    ]);
  }


  public function showone($wdid)
  {
    $rggroup = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($wdid);
    if (!$rggroup)
    {
      return $this->render('rggroup/showone.html.twig', [    'rgyear'=>$this->rgyear,'message' =>  'rggroup not Found',]);
    }
    if(!$rggroup->getKML() or !$this->mapserver->ismap($rggroup->getKML()))
    {
      $rggroup->setKML($this->mapserver->findmap($rggroup->getRggroupid(),$this->rgyear));
    }
    $subgroups = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($wdid);
     $spareroadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findSpareRoadgroupsinGroup($wdid);
    $sglist = array();
    foreach ($subgroups as $asubgroup)
    {
      $swid =  $asubgroup->getRgsubgroupid();
      $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swid, $this->rgyear);
      $rglist= array();
      $totalhh=0;
      $calchh= 0;
     foreach ($roadgroups as $aroadgroup)
      {
        $rglist[$aroadgroup->getRoadgroupid()]=$aroadgroup->getKML();
      }
      $sglist[$swid]=$rglist;
      $asubgroup->total= $totalhh;
      $asubgroup->calculated =$calchh;
    }
    $extraroadgroups =  $this->getDoctrine()->getRepository("App:Roadgroup")->findLooseRoadgroups();
    return $this->render('rggroup/showone.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'rggroup'=> $rggroup,
    'subgroups'=> $subgroups,
    'roadgroups'=>$extraroadgroups,
    'sglist'=>$sglist,
    'back'=>"/rggroup/showall"
    ]);
  }



  public function edit($rgid)
  {
    $request = $this->requestStack->getCurrentRequest();
    if($rgid)
    {
      $rggroup = $this->getDoctrine()->getRepository('App:Rggroup')->findOne($rgid);
    }
    if(! isset($rggroup))
    {
      $rggroup = new Rggroup();
    }
    $form = $this->createForm(RggroupForm::class, $rggroup);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rggroup);
        $entityManager->flush();
        $pid = $rggroup->getRggroupid();
        return $this->redirect("/rggroup/edit/".$rgid);
      }
    }

    return $this->render('rggroup/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'objid'=>$rgid,
      'rggroup'=>$rggroup,
      'returnlink'=>'/rggroup/problems',
      ));
  }



  public function delete($rgid)
  {
    $subgroup = $this->getDoctrine()->getRepository('App:Rggroup')->findOne($rgid);
    $wdid = $subgroup->getRggroupid();
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($subgroup);
    $entityManager->flush();
    return $this->redirect("/rggroup/showall/");
  }

  public function newrggroup()
  {
    $request = $this->requestStack->getCurrentRequest();
    $rggroup = new Rggroup();
    $form = $this->createForm(RggroupForm::class, $rggroup);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rggroup);
        $entityManager->flush();
        $pid = $rggroup->getRggroupid();
        return $this->redirect("/rggroup/showall");
      }
    }

    return $this->render('rggroup/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'rggroup'=>$rggroup,
      'returnlink'=>'/rggroup/showall',
      ));
  }

  public function showsubgroup($swdid)
  {
    $subgroup = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findOne($swdid);
    if (!$subgroup) {
      return $this->render('subgroup/showone.html.twig', [   'rgyear'=>$this->rgyear,  'message' =>  'subgroup not Found',]);
    }
    $rggroupid =  $subgroup->getRggroupid();
    $rggroup = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($rggroupid);
    $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swdid, $this->rgyear);
    $spareroadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findSpareRoadgroupsinGroup($rggroupid);
    $extrastreets =null;
    return $this->render('subgroup/showone.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'rggroup'=>$rggroup,
    'subgroup'=>$subgroup,
    'roadgroups'=> $roadgroups,
    'spareroadgroups'=>$spareroadgroups,
    'streets'=>$extrastreets,
    'back'=>"/rggroup/show/".$rggroupid,
    ]);
  }



 public function addroadgroup($wdid,$swdid,$rgid)
  {
          $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid, $this->rgyear);
           $roadgroup->setRgsubgroupid ($swdid);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($roadgroup);
      $entityManager->flush();
      return $this->redirect("/rgsubgroup/show/".$swdid);
  }



  public function updatesubgroup($swdid)
  {
    $this->processupdatesubgroup($swdid);
     return $this->redirect("/rgsubgroup/show/".$swdid);

  }

   public function processupdatesubgroup($swdid)
  {
    $subgroup = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findOne($swdid);
    $rggroupid =  $subgroup->getRggroupid();
    $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swdid, $this->rgyear);
    $geodata= new Geodata();
    $hh = 0;
    $ee=0;
    $rdds=0;
    $rgs=0;
     $totplvw = 0;
     $totplvn =0;
   dump("here we are");
    foreach ($roadgroups as $broadgroup)
    {
      $rgid = $broadgroup->getRoadgroupid();
         dump("here we are 2");
      $response = $this->forward('App\Controller\RoadgroupController:UpdateRoadgroup', ['rgid'  => $rgid,]);
         dump("here we are3c");
      $this->makeGeodataforRoadgroup($rgid);
      $aroadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
      $geodata->mergeGeodata_obj($aroadgroup->getGeodata_obj());
      $rgs ++;
      $rdds= $rdds+$aroadgroup->getStreets();
      $rghh = $aroadgroup->getHouseholds();
      $hh = $hh + $aroadgroup->getHouseholds();
      $ee =$ee+ $aroadgroup->getElectors();
      $totplvw += $aroadgroup->getPLVW();
      $totplvn += $aroadgroup->getPLVN();;
    }
      if($geodata->maxlat <-350)
      {
        $agroup = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($rggroupid);

          $geodata = $agroup->getGeodata();

      }

      $subgroup->setHouseholds($hh);
      $subgroup->setElectors($ee);
      $subgroup->setStreets($rdds);
      $subgroup->setRoadgroups($rgs);
      $subgroup->setPLVW($totplvw);
      $subgroup->setPLVN($totplvn);
      $subgroup->setGeodata($geodata);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($subgroup);
      $entityManager->flush();
     // $this->updateGroup($rggroupid);

     $rggroup = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($rggroupid );
     dump($rggroup);
  }


   public function updategroup($wdid)
  {
    $agroup = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($wdid);
    $agroupid =  $agroup->getRggroupid();
    $subgroups = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($wdid);
    $geodata= new Geodata();
    $hh = 0;
    $ee=0;
    $rdds=0;
    $rgs=0;
    foreach ($subgroups as $asubgroup)
    {
       $swdid = $asubgroup->getRgsubgroupId();
       $this->processupdatesubgroup($swdid);
       $geodata->mergeGeodata_obj($asubgroup->getGeodata_obj());
      $rgs ++;
      $rdds= $rdds+$asubgroup->getStreets();
      $hh = $hh + $asubgroup->getHouseholds();
      $ee =$ee+ $asubgroup->getElectors();
    }
      $agroup->setHouseholds($hh);
      $agroup->setElectors($ee);
      $agroup->setStreets($rdds);
      $agroup->setRoadgroups($rgs);
      if($geodata->maxlat <-350)
      {
       dump($agroup->getKml());
        if($agroup->getKml())
        {
          $geodata = $this->mapserver->scanRoute($agroup->getKml());
        }
      }
      $agroup->setGeodata($geodata);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($agroup);
      $entityManager->flush();
     return $this->redirect("/rggroup/show/".$wdid);
  }

  public function heatmap($rgid)
  {

    $rggroup = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($rgid);
    if (!$rggroup)
    {
      return $this->render('rggroup/showall.html.twig', [ 'message' =>  'RGgroup not Found',]);
    }
    $rglist= array();

      $subgroups = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($rgid);
      foreach ($subgroups as $asubgroup)
      {
        $swid =  $asubgroup->getRgsubgroupid();
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swid,$this->rgyear);
        foreach ($roadgroups as $aroadgroup)
        {
           if($aroadgroup->getElectors()>0)
             $labness =  $aroadgroup->getPLVN()/$aroadgroup->getElectors();
          else
             $labness=0;
          $rg = array();
          $rg["kml"] = $aroadgroup->getKML();
          $rg["priority"] = $labness;
          $rg["color"] = $this->mapserver->makeColor($labness);
          $rg['rgid'] = $aroadgroup->getRoadgroupid();
          $rglist[]=$rg;
        }
      }
    return $this->render('rggroup/heatmap.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'rggroup' => $rggroup,
    'rglist'=>$rglist,
    'back'=>"/rggroup/showall"
    ]);
  }



  public function Newsubgroup($wdid)
  {
    $request = $this->requestStack->getCurrentRequest();
    $subgroup = new Rgsubgroup();
    $subgroup->setRggroupid($wdid);
    $form = $this->createForm(NewRgsubgroupForm::class, $subgroup);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($subgroup);
        $entityManager->flush();
        $pid = $subgroup->getRgsubgroupid();
        return $this->redirect("/rggroup/show/".$wdid);
      }
    }

    return $this->render('subgroup/new.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'rggroupid'=>$wdid,
      'subgroup'=>$subgroup,
      'returnlink'=>'/rggroup/show/'.$wdid,
      ));
  }

  public function Editsubgroup($swdid)
  {
    $request = $this->requestStack->getCurrentRequest();
    if($swdid != "")
    {
      $subgroup = $this->getDoctrine()->getRepository('App:Rgsubgroup')->findOne($swdid);
    }
    if(! isset($subgroup))
    {
      $subgroup = new Rgsubgroup();
    }
    $form = $this->createForm(RgsubgroupForm::class, $subgroup);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        // $person->setContributor($user->getUsername());
        // $person->setUpdateDt($time);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($subgroup);
        $entityManager->flush();
        $pid = $subgroup->getRgsubgroupid();
        return $this->redirect("/rgsubgroup/show/".$swdid);
      }
    }

    return $this->render('subgroup/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'subgroup'=>$subgroup,
      'returnlink'=>'/street/problems',
      ));
  }


  public function Deletesubgroup($swdid)
  {

    $subgroup = $this->getDoctrine()->getRepository('App:Rgsubgroup')->findOne($swdid);
    $wdid = $subgroup->getRggroupid();

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($subgroup);
    $entityManager->flush();

    return $this->redirect("/rggroup/show/".$wdid);

  }


  public function makeGeodataforRoadgroup($rgid)
   {
     $roadgroup=  $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
     $streets =  $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid,$this->rgyear);

     $totalhouseholds = 0;
     $totalelectors = 0;
     $totalstreets = 0;
     $totalsteps =0;
     $totplvw = 0;
     $totplvn =0;
     $geodata = new Geodata;
     foreach($streets as $astreet)
     {
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

