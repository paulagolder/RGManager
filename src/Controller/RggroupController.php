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
use App\Service\MapServer;

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
    $bounds = $this->mapserver->newBounds();
    for($i=0; $i<count($rggroups); $i++)
    {
      $rggroup = $rggroups[$i];
      $rgs = $this->getDoctrine()->getRepository("App:Roadgroup")->findRoadgroupsinRGGroup($rggroup->getRggroupid(),$this->rgyear);
      $nrg = 0;
      $sumhh=0;
      for($j=0;$j<count($rgs);$j++)
      {
        $rgid = $rgs[$j]->getRoadgroupId();
        $aroadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
        dump( $aroadgroup->getGeodata());
        $bounds = $this->mapserver->expandboundsobj($bounds, $aroadgroup->getGeodata());
        $hh = $rgs[$j]->getHouseholds();
        $sumhh += $hh;
      //  $mpath = $maproot."roadgroups/".$rgid.".kml";
        $kml = $rgs[$j]->getKML();
        if($kml)
        {
          $mpath = $maproot."/roadgroups/".$kml;
          $mpath = str_replace("//","/",$mpath);
          if(file_exists($mpath))
          {
            $rgs[$j]->setKML( $kml);
            $nrg++;
          }
        }

      }
      $rggroup->total= $sumhh;
      $rggroup->roadgroups= count($rgs);
      $rggroup->rgfound= $nrg;
      $rggroup->roadgrouplist= $rgs;
      $rggroup->roadgroups= count($rgs);
      $rggroups[$i]=$rggroup;
    }

    $extraroadgroups =  $this->getDoctrine()->getRepository("App:Roadgroup")->findLooseRoadgroups();
    return $this->render('rggroup/showall.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'heading' => 'The rggroups',
    'topmap'=>$topmap,
    'bounds'=> $bounds,
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
    if(!$this->mapserver->ismap($rggroup->getKML()))
    {
      $rggroup->setKML($this->mapserver->findmap($rggroup->getRggroupid(),$this->rgyear));
    }
      $rggroup->setHouseholds($this->getDoctrine()->getRepository("App:Rggroup")->countHouseholds($rggroup->getRggroupid(), $this->rgyear));

    $subgroups = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($wdid);

    $sglist = array();
    $bounds = $this->mapserver->newBounds();
    foreach ($subgroups as $asubgroup)
    {
      $swid =  $asubgroup->getRgsubgroupid();
      $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swid, $this->rgyear);
      $rglist= array();
      $totalhh=0;
      $calchh= 0;
      foreach ($roadgroups as $aroadgroup)
      {
        $bounds = $this->mapserver->expandboundsobj($bounds, $aroadgroup->getGeodata());
        $totalhh += $aroadgroup->getHouseholds();
        if(!$this->mapserver->ismap($aroadgroup->getKML()))
        {
          //$aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupid(),$this->rgyear));
        }
        $rglist[$aroadgroup->getRoadgroupid()]=$aroadgroup->getKML();
        $hh = $this->getDoctrine()->getRepository("App:Roadgroup")->countHouseholds($aroadgroup->getRoadgroupId(),$this->rgyear);
        $calchh += $hh;
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
    'bounds'=> $bounds,
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
    $bounds = $this->mapserver->newBounds();
    foreach ($roadgroups as &$aroadgroup)
    {
      $bounds = $this->mapserver->expandboundsobj($bounds, $aroadgroup->getGeodata());
      $est = $this->getDoctrine()->getRepository("App:Roadgroup")->countHouseholds($aroadgroup->getRoadgroupId(),$this->rgyear);
      $aroadgroup->{"calculated"} = $est;
    }

    $extrastreets =null;
    return $this->render('subgroup/showone.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'rggroup'=>$rggroup,
    'subgroup'=>$subgroup,
    'roadgroups'=> $roadgroups,
    'bounds'=> $bounds,
    'streets'=>$extrastreets,
    'back'=>"/rggroup/show/".$rggroupid,
    ]);
  }

  public function heatmap()
  {
    $mappath= $this->container->get('parameter_bag')->get('mappath');
    $maproot = $this->container->get('parameter_bag')->get('maproot');
    $topmap = $this->container->get('parameter_bag')->get('topmap');
    $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
    if (!$rggroups)
    {
      return $this->render('rggroup/showall.html.twig', [ 'message' =>  'RGgroups not Found',]);
    }
    $rglist= array();
    foreach($rggroups as $arggroup)
    {
      $subgroups = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($arggroup->getRggroupid());
      foreach ($subgroups as $asubgroup)
      {
        $swid =  $asubgroup->getRgsubgroupid();
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swid,$this->rgyear);
        foreach ($roadgroups as $aroadgroup)
        {
          if(!$this->mapserver->ismap($aroadgroup->getKML()))
          {
            $aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupid(),$this->rgyear));
          }
          $labness = intval($aroadgroup->getPrioritygroup());
          $aroadgroup->setPrioritygroup($labness);
          $rg = array();
          $rg["kml"] = $aroadgroup->getKML();
          $rg["priority"] = $labness;
          $rg["color"] = $this->makeColor($labness);
          // dump($rg);
          $rglist[]=$rg;
        }
      }
    }
    return $this->render('rggroup/heatmap.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
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



  public function xmakexml()
  {
    $xmlout = "";
    $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
    if (!$rggroups) {
      return "";
    }
    foreach (  $rggroups as $arggroup )
    {
      $subgroups =   $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($arggroup->getRggroupid());
      $arggroup->subgroups = $subgroups;
      foreach($subgroups as $asubgroup)
      {
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($asubgroup->getRgsubgroupid());
        $asubgroup->roadgrouplist=$roadgroups;
        foreach($roadgroups as $aroadgroup)
        {
          $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($aroadgroup->getRoadgroupid(),$this->rgyear);

          $aroadgroup->{'streets'}= $streets;
        }
      }
    }

    foreach (  $rggroups as $arggroup )
    {
      $xmlout .= $arggroup->makexml();
      $xmlout .= "\n";
    }
    return $xmlout;;
  }





}

