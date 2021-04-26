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

use Symfony\Component\HttpFoundation\Response;
//use Dompdf\Dompdf;


use App\Entity\Delivery;
use App\Entity\Rggroup;
use App\Entity\Rgsubgroup;
use App\Form\Type\DeliveryForm;
use App\Entity\DeliverytoRoadgroup;

use App\Service\MapServer;

//use App\Service\PDF;
//use Fpdf\Fpdf;
//use Dompdf\Exception;

//require('fpdf.php');

class DeliveryController extends AbstractController
{

    private $A4w = 210;
    private $A4h = 297;

    private $requestStack;
    private $mapserver;
    private $rgyear ;

    public function __construct( RequestStack $request_stack, MapServer $mapserver )
    {
        $this->requestStack = $request_stack;
        $this->mapserver = $mapserver;
        $mapserver->load();
        $this->rgyear  = $this->requestStack->getCurrentRequest()->cookies->get('rgyear');
    }

    public function showcurrent()
    {
        $deliveries = $this->getDoctrine()->getRepository("App:Delivery")->findCurrent();
        if (!$deliveries)
        {
            return $this->render('delivery/showcurrent.html.twig', [ 'message' =>  'No Deliveries Found', 'rgyear'=> $this->rgyear,]);
        }

        foreach($deliveries as &$delivery)
        {
          $result = $this->countDeliveries($delivery->getDeliveryId(),$this->rgyear);
          $delivery->{"target"}=$result["target"];
          $delivery->{"completed"}=$result["completed"];
        }

        return $this->render('delivery/showcurrent.html.twig',
            [
                'rgyear' => $this->rgyear,
                'message' =>  '' ,
                'deliveries'=>$deliveries,
                'back'=>"/"
            ]);
    }


    public function schedule($dvyid)
    {
        $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
        if (!$delivery)
        {
            return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
        }
        $rgs= $this->getDoctrine()->getRepository("App:Delivery")->findDeliveryRoadgroups($dvyid,$this->rgyear);
      $rggroups = $this->maketree($dvyid,$rgs);
        $sparergs = [];
        $allrgs =  $this->getDoctrine()->getRepository("App:Delivery")->findCandidateDeliveryRoadgroups($delivery,$this->rgyear);
        $totalhouseholds = 0;
        foreach($allrgs as $rg)
        {
           if(!array_key_exists($rg["roadgroupid"], $rgs))
           {
              $sparergs[$rg["roadgroupid"]] = $rg;
           } else
           {
              $totalhouseholds += $rg["households"];
           }
        }
        $delivery->Households = $totalhouseholds;
        $rgsparegroups = $this->maketree($dvyid,$sparergs);
        return $this->render('delivery/showone.html.twig',
            [
                'rgyear' => $this->rgyear,
                'message' =>  '' ,
                'delivery'=>$delivery,
                'rggroups'=>$rggroups,
                'rgsparegroups'=>$rgsparegroups,
                'back'=>"/delivery/showcurrent"
            ]);
    }

    public function mapschedule($dvyid)
    {
        $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
        if (!$delivery)
        {
            return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
        }
        $rgs= $this->getDoctrine()->getRepository("App:Delivery")->findDeliveryRoadgroups($dvyid,$this->rgyear);
        $rggroups = $this->maketree($dvyid,$rgs);
        $bounds = $this->mapserver->newBounds();
        $totalhouseholds =0;
        foreach($rgs as $rg)
        {
        $aroadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rg["roadgroupid"],$this->rgyear);
        $bounds = $this->mapserver->expandbounds($bounds, $aroadgroup->getBounds());
         $totalhouseholds += $rg["households"];

        }
        $delivery->Households = $totalhouseholds;
        $allrgs =  $this->getDoctrine()->getRepository("App:Delivery")->findCandidateDeliveryRoadgroups($delivery,$this->rgyear);
        dump($allrgs);

        return $this->render('delivery/showmap.html.twig',
            [
                'rgyear' => $this->rgyear,
                'message' =>  '' ,
                'delivery'=>$delivery,
                'rgs'=>$rgs,
                'bounds'=> $bounds,
                'rggroups'=>$rggroups,
                'allrgs'=>$allrgs,
                'back'=>"/delivery/showcurrent"
            ]);
    }


    public function viewdetail($dvyid, $rgsel)
    {

        $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
        if (!$delivery)
        {
            return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
        }
        $rgs= $this->getDoctrine()->getRepository("App:Delivery")->findDeliveryRoadgroups($dvyid,$this->rgyear);

        $rggroups = self::maketree($dvyid,$rgs);
        dump($rggroups);
        $sparergs = [];
        $allrgs =  $this->getDoctrine()->getRepository("App:Delivery")->findCandidateDeliveryRoadgroups($delivery,$this->rgyear);
        $totalhouseholds =0;
        foreach($allrgs as $rg)
        {
         if(!array_key_exists($rg["roadgroupid"], $rgs))
         {
         $sparergs[] = $rg;
         }
         else
         {
         $totalhouseholds += $rg["households"];
         }
        }
        $delivery->Households = $totalhouseholds;
        dump($delivery);
         dump($rggroups);
        $xrgsparegroups = self::maketree($dvyid,$sparergs);
     // $xrgsparegroups = [];
            dump($rggroups);
             // dump($rgsparegroups);
        return $this->render('delivery/showone.html.twig',
            [
                'rgyear' => $this->rgyear,
                'message' =>  '' ,
                'delivery'=>$delivery,
                'rggroups'=>$rggroups,
                'rgid'=>$rgsel,
                'rgsparegroups'=>$xrgsparegroups,
                'back'=>"/delivery/showcurrent"
            ]);
    }


     public function manage($dvyid, $rgsel)
    {
        $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
        $district = $this->getDoctrine()->getRepository("App:District")->findOne($delivery->getDistrict());
        if (!$delivery)
        {
            return $this->render('Delivery/showone.html.twig', [ 'message' =>  'Delivery not Found',]);
        }
        $dgs= $this->getDoctrine()->getRepository("App:DeliverytoRoadgroup")->findRoadgroups($dvyid);
          $totalhouseholds =0;
           $totaldelivered = 0;
        foreach($dgs as $dg)
        {
         $totalhouseholds += $dg->getHouseholds();
         if($dg->getCompleted())
           $totaldelivered += $dg->getHouseholds();
        }
         $delivery->Households = $totalhouseholds;
          $delivery->Completed = $totaldelivered;
        $rggroups = self::maketree2($dvyid,$dgs);
        return $this->render('delivery/manage.html.twig',
            [
                'rgyear' => $this->rgyear,
                'message' =>  '' ,
                'delivery'=>$delivery,
                'district'=>$district,
                'rggroups'=>$rggroups,
                'dgs'=>$dgs,
                'rgid'=>$rgsel,
                'back'=>"/delivery/showcurrent"
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
        $dtorg->setCompleted($input["achieved"][$i]);
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


     public function parseroadgroups($dvyid)
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
         return $this->redirect("/delivery/schedule/$dvyid");
    }



    public function maketree($dvyid,$yrglist)
    {
     $xrggroups= array();
     foreach($yrglist as $key=>$rg)
     {
       $rgid = $rg["roadgroupid"];
       $rggrp =  $rg["rggroupid"];
       $rgsubgrp =   $rg["rgsubgroupid"];
       if(! array_key_exists ( $rggrp , $xrggroups ) )
       {
         $anobj = new Rggroup();
         $oldobj = $this->getDoctrine()->getRepository('App:Rggroup')->findOne($rggrp);
         $anobj->copy($oldobj);
         $xrggroups[$rggrp]["children"] = array();
         $xrggroups[$rggrp]["group"] =  $anobj;
         $xrggroups[$rggrp]["group"]->setHouseholds(0);

       }
       if(! array_key_exists ( $rgsubgrp , $xrggroups[$rggrp]["children"] ) )
       {
          $asubobj = new Rgsubgroup();
          $oldsubobj = $this->getDoctrine()->getRepository('App:Rgsubgroup')->findOne($rgsubgrp);
          $asubobj->copy($oldsubobj);
          $xrggroups[$rggrp]["children"][$rgsubgrp]["children"] = array();
          $xrggroups[$rggrp]["children"][$rgsubgrp]["group"] = $asubobj;
       }
      // if(! array_key_exists ( $rgid ,  $rggroups[$rggrp]["children"][$rgsubgrp]["children"]) )
       {
          $thisrg =  $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid,$this->rgyear);
          if($thisrg)
          {
          $xrggroups[$rggrp]["children"][$rgsubgrp]["children"][$rgid] =  $thisrg;
          $hh = $thisrg->getHouseholds();
          $xrggroups[$rggrp]["group"]->addHouseholds($hh);
         }
       }
      }
      return $xrggroups;
     }


 public function maketree2($dvyid,$rglist)
    {
     $xrggroups= array();
     foreach($rglist as $key=>$rg)
     {
       $rgid = $rg->getRoadgroupId();
       $rggrp =  $rg->getRggroupId();
       $rgsubgrp =  $rg->getRgsubgroupId();
       if(! array_key_exists ( $rggrp , $xrggroups ) )
       {
         $anobj = new Rggroup();
         $oldobj = $this->getDoctrine()->getRepository('App:Rggroup')->findOne($rggrp);
         $anobj->copy($oldobj);
         $xrggroups[$rggrp]["children"] = array();
         $xrggroups[$rggrp]["group"] =  $anobj;
         $xrggroups[$rggrp]["group"]->setHouseholds(0);
         $xrggroups[$rggrp]["group"]->setCompletions(0);
         $xrggroups[$rggrp]["group"]->initGeodata();

       }
       if(! array_key_exists ( $rgsubgrp , $xrggroups[$rggrp]["children"] ) )
       {
          $asubobj = new Rgsubgroup();
          $oldsubobj = $this->getDoctrine()->getRepository('App:Rgsubgroup')->findOne($rgsubgrp);
          $asubobj->copy($oldsubobj);
          $xrggroups[$rggrp]["children"][$rgsubgrp]["children"] = array();
          $xrggroups[$rggrp]["children"][$rgsubgrp]["group"] = $asubobj;
          $xrggroups[$rggrp]["children"][$rgsubgrp]["group"]->setHouseholds(0);
          $xrggroups[$rggrp]["children"][$rgsubgrp]["group"]->setCompletions(0);
          $xrggroups[$rggrp]["children"][$rgsubgrp]["group"]->initGeodata();
       }
          $thisrg =  $this->getDoctrine()->getRepository('App:DeliverytoRoadgroup')->findOne($dvyid,$rgid);
          $aroadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
          $xrggroups[$rggrp]["children"][$rgsubgrp]["children"][$rgid] =  $thisrg;
          $hh = $thisrg->getHouseholds();
          $geodata = $aroadgroup->getGeodata();
          $xrggroups[$rggrp]["group"]->addHouseholds($hh);
          $xrggroups[$rggrp]["group"]->expandBounds($geodata);
          $xrggroups[$rggrp]["children"][$rgsubgrp]["group"]->addHouseholds($hh);
          $xrggroups[$rggrp]["children"][$rgsubgrp]["group"]->expandBounds($geodata);
          if($thisrg->getCompleted())
          {
             $xrggroups[$rggrp]["group"]->addCompletions($hh);
             $xrggroups[$rggrp]["children"][$rgsubgrp]["group"]->addHouseholds($hh);
          }
      }
      return $xrggroups;
     }


   public function newdelivery()
    {
        $request = $this->requestStack->getCurrentRequest();
        $delivery = new delivery();
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

        return $this->render('delivery/edit.html.twig', array(
           'rgyear' => $this->rgyear,
            'form' => $form->createView(),
            'delivery'=>$delivery,
            'returnlink'=>'/delivery/showcurrent',
            ));
    }

     public function edit($dvyid)
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






    public function Exportcsv($dvyid)
    {
            $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
            $file = "documents/".$delivery->getName()."_".$this->rgyear.".csv";
            $csvout = "";
            $csvout .= "RD-Groups, RD-Sugroups, Roadgroups, Households \n\n";
            $csvout .= $this->makecsv();
            $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
            fwrite($handle, $csvout) or die ("ERROR: Cannot write the file.");
            fclose($handle);
            $this->addFlash('notice','csv file saved'.$file );
            return $this->redirect("/");
    }





     public function makecsv()
    {
         $csvout = "";
         $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
        if (!$rggroups)
        {
            return "";
        }
        foreach ($rggroups as $arggroup )
        {
           $subgroups =   $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($arggroup->getRggroupid());
           $arggroup->{'subgroups'} = $subgroups;
           foreach($subgroups as $asubward)
           {
              $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($asubward->getRgsubgroupid(),$this->rgyear);
              $asubward->{'roadgrouplist'}=$roadgroups;
              foreach($roadgroups as $aroadgroup)
              {
                $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($aroadgroup->getRoadgroupid(),$this->rgyear);
                $aroadgroup->{'streets'}= $streets;
              }
           }
        }
        foreach ($rggroups as $arggroup )
        {
           $csvout .= $arggroup->makecsv();
        }
        return $csvout;
      }


      public function addRoadgroup($dvyid,$rgsel)
      {
           $rgid = str_replace("RG_","",$rgsel);
           $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid,$this->rgyear);
           $sparergs= $this->getSpareRoadgroups($dvyid, $this->rgyear);
           if($rgid =="ALL")
           {
                $entityManager = $this->getDoctrine()->getManager();
                foreach( $sparergs as $rg)
                {
                  $rgid = $rg["roadgroupid"];
                  $roadgroup =  $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid);
                  $adtrg = new DeliverytoRoadgroup();
                  $adtrg->setDeliveryId($dvyid);
                  $adtrg->setRoadgroupId($rg["roadgroupid"]);
                  $adtrg->setRggroupId($roadgroup->getRggroupId());
                  $adtrg->setRgsubgroupId($roadgroup->getRgsubgroupId());
                  $adtrg->setHouseholds($roadgroup->getHouseholds());
                  $adtrg->setKML($roadgroup->getKml());
                  $entityManager->persist($adtrg);
                }
                $entityManager->flush();
           }
           else
           {
               $entityManager = $this->getDoctrine()->getManager();
               foreach( $sparergs  as $key=>$arg)
               {
                  if(strpos($key , $rgid) !== false)
                  {
                    $roadgroup =  $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($key,$this->rgyear);
                    $adtrg = new DeliverytoRoadgroup();
                    $adtrg->setDeliveryId($dvyid);
                    $adtrg->setRoadgroupId($key);
                      $adtrg->setRggroupId($roadgroup->getRggroupId());
                       $adtrg->setRgsubgroupId($roadgroup->getRgsubgroupId());
                    $adtrg->setHouseholds($roadgroup->getHouseholds());
                    $adtrg->setKML($roadgroup->getKml());
                    $entityManager->persist($adtrg);
                  }
               }
               $entityManager->flush();
           }
             return $this->redirect( "/delivery/viewdetail/".$dvyid."/".$rgsel);
      }

      public function addRound($dvyid,$rgid)
      {
           $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid,$this->rgyear);
           $entityManager = $this->getDoctrine()->getManager();
                    $roadgroup =  $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
                    $adtrg = new DeliverytoRoadgroup();
                    $adtrg->setDeliveryId($dvyid);
                    $adtrg->setRoadgroupId($rgid);
                      $adtrg->setRggroupId($roadgroup->getRggroupId());
                       $adtrg->setRgsubgroupId($roadgroup->getRgsubgroupId());
                    $adtrg->setHouseholds($roadgroup->getHouseholds());
                    $adtrg->setKML($roadgroup->getKml());
                    $entityManager->persist($adtrg);
               $entityManager->flush();
             return $this->redirect( "/delivery/mapschedule/".$dvyid);
      }


      public function removeRoadgroup($dvyid,$rgsel)
      {
           $rgid = str_replace("RG_","",$rgsel);
           $rgsel = "RG_".$rgid;
           $entityManager = $this->getDoctrine()->getManager();
           $rgs= $this->getCommittedRoadgroups($dvyid, $this->rgyear);
           foreach($rgs as $key=>$rg)
           {
            if(strpos($key , $rgid) !== false)
            {
              $roadgrouplink =  $this->getDoctrine()->getRepository("App:DeliverytoRoadgroup")->findOne($dvyid,$key);
              $entityManager->remove($roadgrouplink);
            }
            else if(strpos($rgid, $key ) !== false)
            {
              $roadgrouplink =  $this->getDoctrine()->getRepository("App:DeliverytoRoadgroup")->findOne($dvyid,$key);
              $entityManager->remove($roadgrouplink);
            }
           }
           $entityManager->flush();
           return $this->redirect( "/delivery/viewdetail/".$dvyid."/".$rgsel);
      }


      public function removeRound($dvyid,$rgid)
      {
           $entityManager = $this->getDoctrine()->getManager();
           $roadgrouplink =  $this->getDoctrine()->getRepository("App:DeliverytoRoadgroup")->findOne($dvyid,$rgid);
           $entityManager->remove($roadgrouplink);
           $entityManager->flush();
           return $this->redirect( "/delivery/mapschedule/".$dvyid);
      }


      public function getSpareRoadgroups($dvyid, $year)
      {
        $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
        $rgs= $this->getDoctrine()->getRepository("App:Delivery")->findDeliveryRoadgroups($dvyid,$this->rgyear);
        $rggroups = $this->maketree($dvyid,$rgs);
        $sparergs = [];
        $allrgs =  $this->getDoctrine()->getRepository("App:Delivery")->findCandidateDeliveryRoadgroups($delivery,$this->rgyear);
        foreach($allrgs as $rg)
        {
           if(!array_key_exists($rg["roadgroupid"], $rgs))
           {
             $sparergs[$rg["roadgroupid"]] = $rg;
           }
        }
        return $sparergs ;
      }


      public function getCommittedRoadgroups($dvyid, $year)
      {
        $rgs= $this->getDoctrine()->getRepository("App:Delivery")->findDeliveryRoadgroups($dvyid,$this->rgyear);
        return $rgs ;
      }

       public function countdeliveries($dvyid,$year)
      {
        $dtrgs= $this->getDoctrine()->getRepository("App:DeliverytoRoadgroup")->findRoadgroups($dvyid);
        $total = 0;
        $done = 0;
        foreach($dtrgs as $dtrg)
        {
          $total += $dtrg->getHouseholds();
          if($dtrg->getCompleted())
          {
            $done += $dtrg->getHouseholds();
          }
        }
        $result = array();
        $result["target"]= $total;
        $result["completed"]=$done;
        return $result ;
      }


    public function exportxml($dvyid)
    {
            $delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($dvyid);
            $dgs= $this->getDoctrine()->getRepository("App:DeliverytoRoadgroup")->findRoadgroups($dvyid);
            $rggroups = self::maketree2($dvyid,$dgs);
            $file = "rgml/".$delivery->getSpacelessName().".rgml";
            $xmlout = "";
            $xmlout .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $xmlout .= "<?xml-stylesheet type='text/xsl' href='./Stylesheets/rgml.xsl' ?>\n";
            $xmlout .= "<delivery Name='".$delivery->getName()."' DeliveryId='".$dvyid."' KML='".$delivery->getKML()."' >\n";
            $xmlout .= $this->makexml($rggroups);
            $xmlout .= "</delivery>\n";
            $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
            fwrite($handle, $xmlout) or die ("ERROR: Cannot write the file.");
            fclose($handle);
            $this->addFlash('notice','xml file saved'.$file );
            return $this->redirect("/");
    }


    public function makexml( $rggroups)
    {
        $xmlout = "";
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
           $xmlout .= "  <rggroup RggroupId='".$arg->getRggroupid()."' Name='".$arg->getName()."' Households='".$arg->getHouseholds()."' KML='".$arg->getKML()."' Bounds='".$arg->getGeodata_json()."' >\n";
           foreach ($subgroups as $asubgroup)
            {
              $asubg  = $asubgroup["group"];
              $xmlout .= "  <rgsubgroup RgsubgroupId='".$asubg->getRgsubgroupid()."' Name='".$asubg->getName()."' Households='".$asubg->getHouseholds()."' Bounds='".$asubg->getGeodata_json()."' >\n";
              $roadgroups=$asubgroup["children"];
              foreach ($roadgroups as $argroup)
              {
                 $rgid = $argroup->getRoadgroupId();
                 $aroadgroup =  $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid,$this->rgyear);
                 $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($aroadgroup->getRoadgroupid(),$this->rgyear);
                 $streettree = array();
                 foreach($streets as $astreet)
                 {
                   $streetname = $astreet->getName();
                   if(array_key_exists($streetname,$streettree))
                   {
                     $streettree[$streetname][] = $astreet;
                   }
                   else
                   {
                     $streettree[$streetname] = array();
                     $streettree[$streetname][] = $astreet;
                   }
                 }
                 $streetlist = array();
                 $trace = false;
                 foreach($streettree as  $streetname=>$astreetgroup)
                 {
                    $allnamed = $this->getDoctrine()->getRepository("App:Street")->findAllbyName($streetname,$this->rgyear);
                    if(count($allnamed)> 1 && count($allnamed)== count($astreetgroup))
                    {
                       $trace= true;
                       $hh = 0;
                       foreach($astreetgroup as  $astreet)
                     {
                        $hh += $astreet->getHouseholds();
                     }
                      $tstreet = $astreetgroup[0];
                      $tstreet->setHouseholds($hh);
                      $tstreet->setPart(null);
                      $tstreet->setQualifier(null);
                      $streetlist[]=$tstreet;
                    }else
                    {
                     foreach($astreetgroup as  $astreet)
                     {
                        $streetlist[]=$astreet;
                     }
                    }
                }
                if($trace)
                {
              //  dump($streettree);
              //  dump($streetlist);
                }
                 $aroadgroup->streets = $streetlist;
                 $kml =  $this->mapserver->findmap($aroadgroup->getRoadgroupId(),$this->rgyear);
                 $aroadgroup->setKML($kml);
                 $xmlout .= $aroadgroup->makeXML($this->rgyear);
              }
              $xmlout .= "  </rgsubgroup>\n";
            }
            $xmlout .= "  </rggroup>\n";
      }
      return $xmlout;;
    }
}
