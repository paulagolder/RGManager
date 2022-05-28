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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

use Symfony\Component\HttpFoundation\Response;
//use Dompdf\Dompdf;


use App\Entity\Delivery;
use App\Entity\Round;
use App\Entity\Geodata;
use App\Entity\Rggroup;
use App\Entity\Rgsubgroup;
use App\Entity\Roadgroup;
use App\Form\Type\DeliveryForm;
use App\Entity\RoundtoRoadgroup;
//use App\Entity\DeliverytoRoadgroup;

use App\Service\MapServer;
use App\Service\TreeServer;



class DeliveryController extends AbstractController
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

  public function showcurrent(Packages $assetPackage)
  {
    $pf = $this->getParameter('publicfolder');
    $deliveries = $this->getDoctrine()->getRepository("App:Delivery")->findCurrent();
    if (!$deliveries)
    {
      return $this->render('delivery/showcurrent.html.twig', [ 'message' =>  'No Deliveries Found', 'rgyear'=> $this->rgyear,]);
    }

    foreach($deliveries as &$delivery)
    {
     // $delivery->{"target"}=0;
     // $delivery->{"delivered"}=0;
      $rounds= $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
      $roundstree = $this->treeserver->makeroundtree_object($rounds);
      $delivery->update($roundstree);
      $rgmlpath ="/rgml/".$delivery->getSpacelessName().".rgml";
      $csvpath ="/csv/".$delivery->getSpacelessName().".csv";
      $rgml = $assetPackage->getUrl($rgmlpath);
      if($rgml)
      {
        $delivery->{"rgml"}= $rgml;
      }
      else
        $delivery->{"rgml"}= null;
      $csv = $assetPackage->getUrl($csvpath);
      if($csv)
      {
        $delivery->{"csv"}= $csv;
      }
      else
        $delivery->{"csv"}= null;
    }

    return $this->render('delivery/showcurrent.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'deliveries'=>$deliveries,
    'back'=>"/"
    ]);
  }


  public function scheduledelivery($dvyid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    if (!$delivery)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    $rounds= $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
    $countrgs = 0;
    $counthh =0;
    $countdelivered=0;
    $counttarget=0;
    $usedrgs= array();
    $geodata = new Geodata;
    foreach($rounds as $rnd)
    {
      $geodata->mergeGeodata_obj($rnd->getGeodata_obj());
    }
    dump($geodata);
    $roundstree = $this->treeserver->makeroundtree_object($rounds);
    dump($roundstree);
    $rgstree = $this->spareRoadgroups($dvyid);
    $delivery->setGeodata($geodata);
    dump($delivery);
    return $this->render('delivery/scheduledelivery.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'delivery'=>$delivery,
    'roundstree'=>$roundstree,
    'rgstree'=>$rgstree,
    'back'=>"/delivery/edit/".$dvyid,
    ]);
  }


  public function showagents($dvyid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    if (!$delivery)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    $rounds= $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
    $agents=[];
    foreach($rounds as $round)
    {
      $agent = $round->getAgent();
      if(!array_key_exists($agent,$agents))
      {
        $a=[];
        $a['label']=$agent;
        $a['round'] = 0;
        $a['target'] = 0;
        $a['completed']=0;
        $agents[$agent]=$a;
      }
      $agents[$agent]['round'] = $agents[$agent]['round']+1;
      $agents[$agent]['target'] = $agents[$agent]['target']+$round->getTarget();
      $agents[$agent]['completed'] = $agents[$agent]['completed']+$round->getCompleted();
    }
     ksort($agents);
    return $this->render('delivery/showagents.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'delivery'=>$delivery,
    'agents'=>$agents,
    'bounds'=>null,
    'back'=>"/delivery/manage/".$dvyid,
    ]);
  }


  public function schedulegroup($dvyid,$grpid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    if (!$delivery)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    $rounds= $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
     $bounds =$this->mapserver->newGeodata();
    $countrgs = 0;
    $usedrgs= array();


    $roundstree =     $this->treeserver->makeroundtree_object($rounds);
    dump($roundstree);
    $grouptree= $roundstree[$grpid]["children"];
    $rgstree = $this->spareRoadgroups($dvyid);
    $group = $roundstree[$grpid]["group"];
    $ggeodata= $group->getGeodata_json();
    dump( $ggeodata);
    return $this->render('delivery/schedulegroup.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'delivery'=>$delivery,
    'group'=> $roundstree[$grpid]["group"],
    'roundstree'=>$grouptree,
    'bounds'=>$ggeodata,
    'rgstree'=>$rgstree,
    'back'=>"/delivery/scheduledelivery/".$dvyid,
    ]);
  }


  public function updategroup($dvyid,$grpid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $rounds= $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
    foreach($rounds as $key=> $rnd)
    {
      $this->roundupdate($dvyid,$rnd->getRoundId());
    }
    return $this->redirect("/delivery/manage/$dvyid");
  }


  public function roundupdate($dvyid,$rndid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $round= $this->getDoctrine()->getRepository("App:Round")->findOne($rndid);
    $entityManager = $this->getDoctrine()->getManager();
    $geodata = new Geodata;
    $rgps =  $this->getDoctrine()->getRepository("App:Round")->getRoadgroups($rndid,$this->rgyear);
    $roadgroups= [];
    foreach($rgps as $rgp)
    {
      $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgp["roadgroupid"],$this->rgyear);
      dump($roadgroup);
      $roadgroups[$rgp["roadgroupid"]]=$roadgroup;
    }
    foreach($roadgroups as $key2=> $roadgroup)
      {
         $geodata->mergeGeodata_obj( $roadgroup->getGeodata_obj());
      }
      $round->setGeodata($geodata);

      $entityManager->persist($round);
    $entityManager->flush();
    return $this->redirect("/delivery/manage/$dvyid");
  }

  public function schedulesubgroup($dvyid,$grpid,$sgrpid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    if (!$delivery)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    $rounds= $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
    $roundstree =     $this->treeserver->makeroundtree_object($rounds);
    $grouptree= $roundstree[$grpid]["children"];
    if (!$grouptree)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Group not Found',]);
    }
    $group =  $roundstree[$grpid]["group"];
    $subgrouptree= $grouptree[$sgrpid]["children"];
    if (!$subgrouptree)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'SubGroup not Found',]);
    }
    $subgroup =  $grouptree[$sgrpid]["group"];
    $countrgs = 0;
    $usedrgs= array();
     $sggeodata = $subgroup->getGeodata_json();
     dump($sggeodata);
    $rgstree = $this->spareRoadgroups($dvyid);
    return $this->render('delivery/schedulesubgroup.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'delivery'=>$delivery,
    'group'=>$group,
    'subgroup'=> $subgroup,
    'roundstree'=>$subgrouptree,
    'bounds'=>$sggeodata,
    'rgstree'=>$rgstree,
    'back'=>"/delivery/schedulegroup/".$dvyid."/".$grpid,
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
    $roundstree =     $this->treeserver->makeroundtree_object($rounds);
    dump($roundstree);
    $delivery->update($roundstree);
    dump($delivery);
    $geodata =new Geodata;
    foreach($roundstree as $key=> $grp)
    {
      $grpgeodata = $grp["group"]-> getGeodata_obj();
      $geodata->mergeGeodata_obj( $grpgeodata);
    }
    $delivery->setGeodata($geodata);
    dump($delivery);
    return $this->render('delivery/managerounds.html.twig',
    [
    'rgyear' => $this->rgyear,
    'message' =>  '' ,
    'delivery'=>$delivery,
    'roundstree'=>$roundstree,
    'back'=>"/delivery/showcurrent"
    ]);
  }

  public function scheduleround($dvyid, $rndid)
  {

    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $round= $this->getDoctrine()->getRepository("App:Round")->findOne($rndid);
    dump($round);
    $roadgroups =  $this->getDoctrine()->getRepository("App:Round")->getRoadgroups($rndid,$this->rgyear);

    $round->{"countrgs"}=count($roadgroups);
    $rgstree = $this->spareRoadgroups($dvyid);

    $bounds =$round->getBounds();
    if(! is_array($bounds))
      $bounds = JSON_decode($bounds);
    dump($bounds);
    $countrgs =0;

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

      return $this->render('round/scheduleround.html.twig',
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




  public function update($dvyid, $rgsel)
  {
    $request = $this->requestStack->getCurrentRequest();
    $data = $request->request->get('form');
    $input = $request->request->all();
    $i=0;
    $entityManager = $this->getDoctrine()->getManager();
    foreach($input["rgid"] as $arid)
    {
      $dtorg =  $this->getDoctrine()->getRepository('App:DeliverytoRoadgroup')->findOne($dvyid,$arid);
      $dtorg->setAgent($input["agent"][$i]);
      $dtorg->setIssuedate($input["issued"][$i]);
      $dtorg->setDelivered($input["achieved"][$i]);
      $entityManager->persist($dtorg);
      $i++;
    }
    $entityManager->flush();
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    if (!$delivery)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    $rgs= $this->getDoctrine()->getRepository("App:Delivery")->findDeliveryRoadgroups($dvyid,$this->rgyear);
    $rggroups = self::maketree($dvyid,$rgs);
    return $this->redirect("/delivery/manage/$dvyid/$rgsel");
  }


  public function xparseroadgroups($dvyid)
  {
    $request = $this->requestStack->getCurrentRequest();
    $data = $request->request->get('form');
    $input = $request->request->all();
    $rglist  = preg_split('/[\ \n\,]+/', $input["rgids"]);
    $i=0;
    $entityManager = $this->getDoctrine()->getManager();
    foreach($rglist as $rg)
    {
      $dtorg =  $this->getDoctrine()->getRepository('App:DeliverytoRoadgroup')->findOne($dvyid,$rg);
      if($dtorg === null)
      {
        $roadgroup =  $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rg,$this->rgyear);
        if($roadgroup !== null)
        {
          $adtrg = new DeliverytoRoadgroup();
          $adtrg->setDeliveryId($dvyid);
          $adtrg->setRoadgroupId($rg);
          $adtrg->setRggroupId($roadgroup->getRggroupId());
          $adtrg->setRgsubgroupId($roadgroup->getRgsubgroupId());
          $adtrg->setHouseholds($roadgroup->getHouseholds());
          $adtrg->setKML($roadgroup->getKml());
          $entityManager->persist($adtrg);
        }
      }

    }
    $entityManager->flush();
    return $this->redirect("/delivery/scheduledelivery/$dvyid");
  }




  public function newdelivery()
  {
    $request = $this->requestStack->getCurrentRequest();
    $delivery = new delivery();
    $delivery->setCreateDate(new \DateTime("now"));
    $form = $this->createForm(DeliveryForm::class, $delivery);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($delivery);
        $entityManager->flush();
        $pid = $delivery->getDeliveryId();
        $newround = new round();
        $newround->setDeliveryId($pid);
        $newround->setName("Default Round");
        $newround->setRggroupId("LDC");
        $entityManager->persist($newround);
        $entityManager->flush();
        return $this->redirect("/delivery/showcurrent/");
      }
    }


    return $this->render('delivery/edit.html.twig', array(
      'rgyear' => $this->rgyear,
      'form' => $form->createView(),
      'delivery'=>$delivery,
      'returnlink'=>'/delivery/showcurrent',
      ));
  }

  public function edit($dvyid,Packages $assetPackage)
  {
    $request = $this->requestStack->getCurrentRequest();
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $form = $this->createForm(DeliveryForm::class, $delivery);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($delivery);
        $entityManager->flush();
        $pid = $delivery->getDeliveryId();
        return $this->redirect("/delivery/showcurrent/");
      }
    }
    $rgmlpath ="/rgml/".$delivery->getSpacelessName().".rgml";
    $csvpath ="/csv/".$delivery->getSpacelessName().".csv";
    dump($csvpath);
    $rgml = $assetPackage->getUrl($rgmlpath);
    if($rgml)
    {
      $delivery->{"rgml"}= $rgml;
    }
    else
      $delivery->{"rgml"}= null;
    $csv = $assetPackage->getUrl($csvpath);
    if($csv)
    {
      $delivery->{"csv"}= $csv;
    }
    else
      $delivery->{"csv"}= null;


    return $this->render('delivery/edit.html.twig', array(
      'rgyear' => $this->rgyear,
      'form' => $form->createView(),
      'delivery'=>$delivery,
      'returnlink'=>'/delivery/showcurrent',
      ));
  }

  public function delete($dvyid)
  {
    $request = $this->requestStack->getCurrentRequest();
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($delivery);
    $entityManager->flush();
    return $this->redirect("/delivery/showcurrent/");
  }


  public function xxmakecsv($dvyid)
  {
    $csvout = "";

    $rggroups = $this->getDoctrine()->getRepository("App:DeliverytoRoadgroup")->findRoadgroups($dvyid);
    dump($rggroups);
    if (!$rggroups)
    {
      return "";
    }

    foreach ($rggroups as $arggroup )
    {
      $csvout .= $arggroup->makecsv();
    }
    return $csvout;
  }


  public function addRoadgroup($dvyid,$rndid,$rgid)
  {
    dump($rgid);
    $round =  $this->getDoctrine()->getRepository("App:Round")->findOne($rndid);
    $roadgroup =  $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
    dump($roadgroup);
    $entityManager = $this->getDoctrine()->getManager();
    $adtrg = new RoundtoRoadgroup();
    $adtrg->setDeliveryId(intval($dvyid));
    $adtrg->setRoundId($rndid);
    $adtrg->setRoadgroupId($rgid);
    $adtrg->setName($roadgroup->getName());
    $adtrg->setRggroupId($roadgroup->getRggroupId());
    $adtrg->setRgsubgroupId($roadgroup->getRgsubgroupId());
    $adtrg->setHouseholds($roadgroup->getHouseholds());
    $entityManager->persist($adtrg);
    $entityManager->flush();
    $roadgrouplist = $this->getDoctrine()->getRepository("App:Round")->getRoadgroups($rndid);
    $entityManager = $this->getDoctrine()->getManager();
    dump($roadgrouplist);
    $round->setValues_array($roadgrouplist);
    dump($round);
    $entityManager->persist($round);
    $entityManager->flush();

    return $this->redirect( "/delivery/scheduleround/".$dvyid."/".$rndid);
  }

  public function addAllRoadgroups($dvyid,$rndid,$rgsubgid)
  {
    dump($rgsubgid);
    $round =  $this->getDoctrine()->getRepository("App:Round")->findOne($rndid);
    $subgroup = $this->getDoctrine()->getRepository("App:Rgsubgroup")->find($rgsubgid);
    $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($rgsubgid,$this->rgyear);
    $existingrgs =  $this->getDoctrine()->getRepository("App:Round")->getRoadgroups($rndid);
    foreach($roadgroups as $roadgroup)
    {
      if(!array_key_exists($roadgroup->getRoadgroupId(),$existingrgs))
      {
        $entityManager = $this->getDoctrine()->getManager();
        $adtrg = new RoundtoRoadgroup();
        $adtrg->setDeliveryId(intval($dvyid));
        $adtrg->setRoundId($rndid);
        $adtrg->setRoadgroupId($roadgroup->getRoadgroupId());
        $adtrg->setName($roadgroup->getName());
        $adtrg->setRggroupId($roadgroup->getRggroupId());
        $adtrg->setRgsubgroupId($roadgroup->getRgsubgroupId());
        $adtrg->setHouseholds($roadgroup->getHouseholds());
        $entityManager->persist($adtrg);
        $entityManager->flush();
      }
    }
    dump($round);
    dump($roadgroups);
    $round->setValues($roadgroups);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($round);
    $entityManager->flush();
    return $this->redirect( "/delivery/scheduleround/".$dvyid."/".$rndid);
  }


  public function removeRoadgroup($dvyid,$rndid,$rgid)
  {

    $round =  $this->getDoctrine()->getRepository("App:Round")->findOne($rndid);
    $RoundtoRoadgroup =  $this->getDoctrine()->getRepository("App:RoundtoRoadgroup")->findRtoR($dvyid,$rndid,$rgid);
    dump( $RoundtoRoadgroup);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($RoundtoRoadgroup );
    $entityManager->flush();
    $roadgroups = $this->getDoctrine()->getRepository("App:Round")->getRoadgroups($rndid);
    $round->setValues_array($roadgroups);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($round);
    $entityManager->flush();

    return $this->redirect( "/delivery/scheduleround/".$dvyid."/".$rndid);
  }


  public function addRound($dvyid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid,$this->rgyear);
    $entityManager = $this->getDoctrine()->getManager();

    $newround = new round();
    $newround->setDeliveryId($dvyid);
    $newround->setName("New Round");

    $entityManager->persist($newround);
    $entityManager->flush();
    $rndid = $newround->getRoundId();
    $newround->setName("Round_".$rndid);
    $entityManager->persist($newround);
    $entityManager->flush();
    return $this->redirect("/delivery/scheduledelivery/".$dvyid);

  }






  public function getCommittedRoadgroups($dvyid, $year)
  {
    $rgs= $this->getDoctrine()->getRepository("App:Delivery")->findDeliveryRoadgroups($dvyid,$this->rgyear);
    return $rgs ;
  }



  public function Exportcsv($dvyid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $rounds= $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
    foreach($rounds as &$round)
    {
      $rgs = $this->getDoctrine()->getRepository("App:Round")->getRoadgroups($round->getRoundId());
      foreach($rgs as $rg)
      {
        $streets = $this->getDoctrine()->getRepository("App:Street")->findGroup($rg["roadgroupid"],$this->rgyear);
      }
   $round->streetlist = $streets;

    }

    $roundstree = $this->treeserver->makeroundtree_object($rounds);
    dump($roundstree);
    $file = "csv/".$delivery->getSpacelessName().".csv";
    $csvout = "";
    $csvout .= "RD-Group, Name, RD-Sugroup,Name, Roadgroup, Name,  Households , Deliveries, Date, Agent, Delivered \n\n";
    $csvout .= $this->makecsv($roundstree);
    $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
    fwrite($handle, $csvout) or die ("ERROR: Cannot write the file.");
    fclose($handle);
    $this->addFlash('notice','csv file saved: '.$file );
    return $this->redirect("/delivery/showcurrent");
  }

  public function makecsv( $rggroups)
  {
    $csvout = "";
    $delivery = $this;
    if (!$delivery)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    if (!$rggroups)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    foreach ($rggroups as $arggroup)
    {
      $arg = $arggroup["group"];
      $subgroups=$arggroup["children"];
      $csvout .= "  ".$arg->getRndgroupid().",".$arg->getName().",,,,,".$arg->getHouseholds().", "." \n";
      foreach ($subgroups as $asubgroup)
      {
        $asubg  = $asubgroup["group"];
        $csvout .= " ,,".$asubg->getRndgroupid().",".$asubg->getName().",,,".$asubg->getHouseholds()."\n";
        $rounds=$asubgroup["children"];
        foreach ($rounds as $around)
        {

          $csvout .= " ,,,,".$around->getLabel().",".$around->getName().",".$around->getTarget()."\n";
        }
        #csvout .= " \n";
      }
      $csvout .= " \n";
    }
    return $csvout;;
  }

  public function exportxml($dvyid)
  {
    $inset = "  ";
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $rounds= $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
    foreach($rounds as $round)
    {
      $roadgrouplist = array();
      $roadgrouplinks =  $this->getDoctrine()->getRepository("App:RoundtoRoadgroup")->findRoadgroups($round->getRoundId());
      foreach($roadgrouplinks as $rglink)
      {

        $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rglink->getRoadgroupid(),$delivery->getYear());
        $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($roadgroup->getRoadgroupid(),$this->rgyear);
        foreach($streets as $astreet)
        {
          $roadgrouplist[]=$astreet;
        }
        $round->setRoadgrouplist($roadgrouplist);
      }
    }
    $roundstree = $this->treeserver->makeroundtree_object($rounds);
    $file = "rgml/".$delivery->getSpacelessName().".rgml";
    $xmlout = "";
    $xmlout .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xmlout .= "<?xml-stylesheet type='text/xsl' href='./Stylesheets/rgml.xsl' ?>\n";
    $xmlout .= "<delivery Name='".$delivery->getName()."' DeliveryId='".$dvyid."' KML='".$delivery->getKML()."' >\n";
    $xmlout .= $this->makexml($roundstree,$inset);
    $xmlout .= "</delivery>\n";
    $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
    fwrite($handle, $xmlout) or die ("ERROR: Cannot write the file.");
    fclose($handle);
    $this->addFlash('notice','xml file saved: '.$file );
    return $this->redirect("/delivery/showcurrent");
  }


  public function makexml($roundtree,$inset=" ")
  {
  dump($roundtree);
    $xmlout = "";
    $delivery = $this;
    if (!$delivery)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    if (!$roundtree)
    {
      return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
    }
    foreach ($roundtree as $arggroup)
    {
      $arg = $arggroup["group"];
      $subgroups=$arggroup["children"];
      $xmlout .= $inset."<rggroup RggroupId='".$arg->getRndgroupid()."' Name='".$arg->getName()."' Households='".$arg->getHouseholds()."' KML='".$arg->getKML()."' Bounds='".$arg->getGeodata_json()."' >\n";
      foreach ($subgroups as $asubgroup)
      {
        $asubg  = $asubgroup["group"];
        $xmlout .= $inset.$inset."<rgsubgroup RgsubgroupId='".$asubg->getRndgroupid()."' Name='".$asubg->getName()."' Households='".$asubg->getHouseholds()."' KML='".$arg->getKML()."' Bounds='".$asubg->getGeodata_json()."' >\n";

        $rounds=$asubgroup["children"];
        foreach ($rounds as $around)
        {
          $xmlout .= $around->makeXML($inset.$inset."    ");
        }
        $xmlout .= $inset.$inset."</rgsubgroup>\n";
      }
      $xmlout .= $inset."</rggroup>\n";
    }
    return $xmlout;;
  }



  public function SpareRoadgroups($dvyid)
  {
    $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $rounds= $this->getDoctrine()->getRepository("App:Round")->findRounds($delivery);
    $usedrgs= array();
    foreach( $rounds as $around)
    {
      $rndid=$around->getRoundId();
      $rgs= $this->getDoctrine()->getRepository("App:Round")->getRoadgroups($rndid,$this->rgyear);
      // $rounds[$rndid]["countrgs"] = count($rgs);
      $usedrgs = array_merge($usedrgs, $rgs);
    }
    $districtid = $delivery->getDistrictId();
    $seats= explode(',',$delivery->getSeatIds());
    $allroadgroups= array();
    if($seats[0]=="")
    {
      $allroadgroups= $this->getDoctrine()->getRepository("App:Delivery")->findDeliveryRoadgroups( $delivery ,$delivery->getYear());
    }
    else
    {

    foreach($seats as $seatid)
    {
      $srgs= $this->getDoctrine()->getRepository("App:Seat")->findRoadgroups($districtid,$seatid,$this->rgyear);
      $allroadgroups = array_merge($allroadgroups,$srgs);
    }
    }
    $rgstree=[];
    if($allroadgroups!=null)
    {
    foreach( $usedrgs as $key=> $usedrg)
    {
      if(array_key_exists($key, $allroadgroups))
      {
        unset($allroadgroups[$key]);
      }
    }
    $rgstree = $this->treeserver->makeroadgrouptree($dvyid,$allroadgroups);
    }

    return $rgstree;
  }


  function makerounds($dvyid)
  {
    $this->getDoctrine()->getRepository("App:Round")->deleteRounds($dvyid);
    $delivery =   $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $sprrgs =  $this->getDoctrine()->getRepository("App:Delivery")->findCandidateDeliveryRoadgroups($delivery,$delivery->getYear());
    dump($sprrgs);
    foreach( $sprrgs as $rg )
    {
          $entityManager = $this->getDoctrine()->getManager();
          $newround = new round();
          $newround->setDeliveryId(intval($dvyid));
          $newround->setLabel($rg["roadgroupid"]);
          $newround->setName($rg["name"]);
          $newround->setRggroupId($rg["rggroupid"]);
          $newround->setRgsubgroupId($rg["rgsubgroupid"]);
          $newround->setHouseholds($rg["households"]);
          $newround->setTarget($rg["households"]);
          $newround->setKML($rg["kml"]);
          $newround->setGeodata($rg["geodata"]);
          $newround->setCompleted(0);
          $newround->setRoadgroups(1);
          $entityManager->persist($newround);
          $entityManager->flush();
          $adtrg = new RoundtoRoadgroup();
          $adtrg->setDeliveryId(intval($dvyid));
          $adtrg->setRoundId($newround->getRoundId());
          $adtrg->setRoadgroupId($rg["roadgroupid"]);
          $adtrg->setName($rg["name"]);
          $adtrg->setRggroupId($rg["rggroupid"]);
          $adtrg->setRgsubgroupId($rg["rgsubgroupid"]);
          $entityManager->persist($adtrg);
          $entityManager->flush();
          $kml = $rg["kml"];
          if($kml != null)
          {
            if(file_exists("maps/roadgroups/".$kml) and !file_exists("maps/rounds/".$kml)  ) {
          copy("maps/roadgroups/".$kml, "maps/rounds/".$kml);
            }
          }
    }
    return $this->redirect("/delivery/showcurrent");
  }

  function makeroundsb($dvyid)
  {
    // $this->SpareRoadgroups($dvyid);
    $delivery =   $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
    $sprrgs =  $this->getDoctrine()->getRepository("App:Delivery")->findCandidateDeliveryRoadgroups($delivery,$delivery->getYear());
    dump($sprrgs);
    foreach( $sprrgs as $rgg )
    {

      foreach($rgg["children"] as $rgsubg)
      {
        foreach($rgsubg["children"] as $rg)
        {
          $entityManager = $this->getDoctrine()->getManager();
          $newround = new round();
          $newround->setDeliveryId(intval($dvyid));
          $newround->setName($rg->getName());
          $newround->setRggroupId($rg->getRggroupid());
          $newround->setRgsubgroupId($rg->getRgsubgroupId());
          $entityManager->persist($newround);
          $entityManager->flush();
          $adtrg = new RoundtoRoadgroup();
          $adtrg->setDeliveryId(intval($dvyid));
          $adtrg->setRoundId($newround->getRoundId());
          $adtrg->setRoadgroupId($rg->getRoadgroupId());
          $adtrg->setName($rg->getName());
          $adtrg->setRggroupId($rg->getRggroupId());
          $adtrg->setRgsubgroupId($rg->getRgsubgroupId());
          $adtrg->setHouseholds($rg->getHouseholds());
          $entityManager->persist($adtrg);
          $entityManager->flush();
        }
      }
    }
    return $this->redirect("/delivery/showcurrent");
  }


}
