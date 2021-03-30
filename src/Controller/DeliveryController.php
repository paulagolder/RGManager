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
        foreach($allrgs as $rg)
        {
           if(!array_key_exists($rg["roadgroupid"], $rgs))
           {
             $sparergs[$rg["roadgroupid"]] = $rg;
           }
        }
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
        foreach($allrgs as $rg)
        {
         if(!array_key_exists($rg["roadgroupid"], $rgs))
         {
         $sparergs[] = $rg;
         }
        }
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
 //gget data and update
        dump($input);
        $i=0;
                  $entityManager = $this->getDoctrine()->getManager();


        foreach($input["rgid"] as $arid)
        {
        $dtorg =  $this->getDoctrine()->getRepository('App:DeliverytoRoadgroup')->findOne($dvyid,$arid);
        $dtorg->setAgent($input["agent"][$i]);
        $dtorg->setIssuedate($input["issued"][$i]);
        $dtorg->setCompleted($input["achieved"][$i]);
        dump($dtorg);
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



    public function maketree($dvyid,$rglist)
    {
     $xrggroups= array();
     foreach($rglist as $key=>$rg)
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
          $thisrg =  $this->getDoctrine()->getRepository('App:DeliverytoRoadgroup')->findOne($dvyid,$rgid);
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
          $thisrg =  $this->getDoctrine()->getRepository('App:DeliverytoRoadgroup')->findOne($dvyid,$rgid);
          $xrggroups[$rggrp]["children"][$rgsubgrp]["children"][$rgid] =  $thisrg;
          $hh = $thisrg->getHouseholds();
         $xrggroups[$rggrp]["group"]->addHouseholds($hh);

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
               // $person->setContributor($user->getUsername());
               // $person->setUpdateDt($time);
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
               // $person->setContributor($user->getUsername());
               // $person->setUpdateDt($time);
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




    public function Exportxml($drid)
    {
            $Delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($drid);
             $kml = $Delivery->getKML();
          if(!$kml)
          {
             $Delivery->setKML($this->mapserver->findDelivery($Delivery->getDeliveryId(),$this->rgyear));
          }
           // $file = "maps/".$drid."_".$this->rgyear.".rgml";
              $file = "rgml/roadgroups.rgml";
            $xmlout = "";
            $xmlout .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $xmlout .= "<?xml-stylesheet type='text/xsl' href='./Stylesheets/rgml.xsl' ?>\n";
            $xmlout .= "<electionDelivery Name='Lichfield City Labour Party' DeliveryId='".$drid."' KML='".$Delivery->getKML()."' >\n";

            $xmlout .= $this->makexml();
            $xmlout .= "</electionDelivery>\n";
            $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
            fwrite($handle, $xmlout) or die ("ERROR: Cannot write the file.");
            fclose($handle);
            $this->addFlash('notice','xml file saved'.$file );
            return $this->redirect("/");
    }

      public function Exportcsv($drid)
    {
            $Delivery = $this->getDoctrine()->getRepository("App:Delivery")->findOne($drid);
            $file = "documents/".$drid."_".$this->rgyear.".csv";
            $csvout = "";

            $csvout .= "RD-Groups, RD-Sugroups, Roadgroups, Households \n\n";

            $csvout .= $this->makecsv();

            $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
            fwrite($handle, $csvout) or die ("ERROR: Cannot write the file.");
            fclose($handle);
            $this->addFlash('notice','csv file saved'.$file );
            return $this->redirect("/");
    }


    public function makexml()
    {
         $xmlout = "";
         $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
        if (!$rggroups) {
            return "";
        }
        foreach (  $rggroups as $arggroup )
        {
           $subwards =   $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($arggroup->getRggroupid());
           $arggroup->{'subwards'} = $subwards;
           foreach($subwards as $asubward)
           {
              $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($asubward->getRgsubgroupid(),$this->rgyear);
              $asubward->{'roadgrouplist'}=$roadgroups;
              foreach($roadgroups as $aroadgroup)
              {
                $streetlist = [];
                $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($aroadgroup->getRoadgroupid(),$this->rgyear);
                foreach($streets as $astreet )
                {
                if(array_key_exists($astreet->getName(), $streetlist))
                {
                  $sstreet = $streetlist[$astreet->getName()];
                  $sstreet->setQualifier($sstreet->getQualifier()." ".$astreet->getQualifier());
                  $streetlist[$astreet->getName()]= $sstreet;
                }else
                  $streetlist[$astreet->getName()] = $astreet;
                }
                $aroadgroup->{'streets'}= $streetlist;
                //dump($aroadgroup);
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


     public function makecsv()
    {
         $csvout = "";
         $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
        if (!$rggroups) {
            return "";
        }
        foreach ($rggroups as $arggroup )
        {

           $subwards =   $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($arggroup->getRggroupid());
           $arggroup->{'subwards'} = $subwards;
           foreach($subwards as $asubward)
           {
              $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($asubward->getRgsubgroupid(),$this->rgyear);
              $asubward->{'roadgrouplist'}=$roadgroups;
              foreach($roadgroups as $aroadgroup)
              {
                $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($aroadgroup->getRoadgroupid(),$this->rgyear);

                $aroadgroup->{'streets'}= $streets;
                //dump($aroadgroup);
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
           dump($sparergs);
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
                  dump($key."=".$rgid);
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



      public function removeRoadgroup($dvyid,$rgsel)
      {
           $rgid = str_replace("RG_","",$rgsel);
           $rgsel = "RG_".$rgid;
           $entityManager = $this->getDoctrine()->getManager();
           $rgs= $this->getCommittedRoadgroups($dvyid, $this->rgyear);
           //dump($rgs);
           foreach($rgs as $key=>$rg)
           {
           dump($key."=".$rgid);
            if(strpos($key , $rgid) !== false)
            {
              dump($key."=".$rgid);
              $roadgrouplink =  $this->getDoctrine()->getRepository("App:DeliverytoRoadgroup")->findOne($dvyid,$key);
              $entityManager->remove($roadgrouplink);
            }
            else if(strpos($rgid, $key ) !== false)
            {
              dump($key."=".$rgid);
              $roadgrouplink =  $this->getDoctrine()->getRepository("App:DeliverytoRoadgroup")->findOne($dvyid,$key);
              $entityManager->remove($roadgrouplink);
            }
           }
           $entityManager->flush();
           return $this->redirect( "/delivery/viewdetail/".$dvyid."/".$rgsel);
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
}
