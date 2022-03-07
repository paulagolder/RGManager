<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\MapServer;

use  App\Entity\Rggroup;
use  App\Entity\Rgsubgroup;
use  App\Entity\Rndgroup;
use App\Entity\Delivery;
use App\Entity\Round;
use Doctrine\ORM\EntityManagerInterface;

class TreeServer
{
  private $requestStack;
  private $em;
  private $rgyear;
  private $mapserver;

  public function __construct(EntityManagerInterface $em,RequestStack $request_stack,MapServer $mapserver)
  {
    $this->requestStack = $request_stack;
    $this->em = $em;
    $this->rgyear  = $this->requestStack->getCurrentRequest()->cookies->get('rgyear');
    $this->mapserver = $mapserver;
  }

  public function makeroundtree_array($rndlist)
  {
    $rndgroups= array();
    foreach($rndlist as $key=>$rnd)
    {
      dump($rnd);
      $hh = $rnd["households"];
      $cmptd = $rnd["completed"];
      $rgs = $rnd["roadgroups"];
      $tgts = $rnd["target"];
      $rndgrpid = $rnd["rggroupid"];
      $rgsubgrpid = $rnd["rgsubgroupid"];
      if(is_null($rndgrpid ))
      {
        $rndgrpid ="AllGroups";
      }
      if(! array_key_exists ( $rndgrpid , $rndgroups ) )
      {
        $oldobj = $this->em->getRepository('App:Rggroup')->findOne($rndgrpid);
        $anobj = new Rndgroup();
        $anobj->setRndgroupId($rndgrpid);
        $anobj->copy($oldobj);
        $rndgroups[$rndgrpid]["children"] = array();
        $rndgroups[$rndgrpid]["group"] =  $anobj;
        $rndgroups[$rndgrpid]["group"]->setHouseholds(0);
      }
      if(is_null($rgsubgrpid ))
      {
        $rgsubgrpid ="AllSubGroups";
      }
      if(! array_key_exists ( $rgsubgrpid , $rndgroups[$rndgrpid]["children"] ) )
      {
        $asubobj = new Rndgroup();
        $oldsubobj = $this->em->getRepository('App:Rgsubgroup')->findOne($rgsubgrpid);
        $asubobj->copy($oldsubobj);
        $asubobj->setRndgroupId($rgsubgrpid);
        $rndgroups[$rndgrpid]["children"][$rgsubgrpid]["children"] = array();
        $rndgroups[$rndgrpid]["children"][$rgsubgrpid]["group"] = $asubobj;
        $rndgroups[$rndgrpid]["children"][$rgsubgrpid]["group"]->setHouseholds(0);
        $rndgroups[$rndgrpid]["children"][$rgsubgrpid]["group"]->setRoadgroups(0);
      }
        $rndgroups[$rndgrpid]["children"][$rgsubgrpid]["group"]->addHouseholds($hh);
        $rndgroups[$rndgrpid]["children"][$rgsubgrpid]["group"]->addRoadgroups($rgs);
        $rndgroups[$rndgrpid]["children"][$rgsubgrpid]["group"]->addTarget($tgts);
        $rndgroups[$rndgrpid]["children"][$rgsubgrpid]["group"]->addCompleted($cmptd);
        $rndgroups[$rndgrpid]["children"][$rgsubgrpid]["children"][$key] =  $rnd;
          $rndgroups[$rndgrpid]["group"]->addHouseholds($hh);
          $rndgroups[$rndgrpid]["group"]->addRoadgroups($rgs);
          $rndgroups[$rndgrpid]["group"]->addTarget($tgts);
          $rndgroups[$rndgrpid]["group"]->addCompleted($cmptd);
    }
    return $rndgroups;
  }


  public function makeroundtree_object($rndlist)
  {
    $rndgroups= array();
    foreach($rndlist as $key=>$rnd)
    {
      $rgid = $rnd->getRoundId();
      $rggrp =  $rnd->getRggroupId();
      $rgsubgrp =  $rnd->getRgsubgroupId();
      if(! array_key_exists ( $rggrp , $rndgroups ) )
      {
        $anobj = new Rndgroup();
        $oldobj = $this->em->getRepository('App:Rggroup')->findOne($rggrp);
        $anobj->copy($oldobj);
        $rndgroups[$rggrp]["children"] = array();
        $rndgroups[$rggrp]["group"] =  $anobj;
        $rndgroups[$rggrp]["group"]->setHouseholds(0);
        $rndgroups[$rggrp]["group"]->setTarget(0);
        $rndgroups[$rggrp]["group"]->setCompleted(0);
        $rndgroups[$rggrp]["group"]->setRoadgroups(0);
        $rndgroups[$rggrp]["group"]->initGeodata();
      }
      if(! array_key_exists ( $rgsubgrp , $rndgroups[$rggrp]["children"] ) )
      {

        $asubobj = new Rndgroup();
        $oldsubobj = $this->em->getRepository('App:Rgsubgroup')->findOne($rgsubgrp);
        $asubobj->copy($oldsubobj);
        $asubobj->Rndgroupid=$rgsubgrp;
        $rndgroups[$rggrp]["children"][$rgsubgrp]["children"] = array();
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"] = $asubobj;
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"]->setHouseholds(0);
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"]->setCompleted(0);
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"]->setTarget(0);
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"]->setRoadgroups(0);
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"]->initGeodata();
      }
   //   $thisrg =  $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid,$this->rgyear);
   //   if($thisrg)
   //   {
        $rndgroups[$rggrp]["children"][$rgsubgrp]["children"][$rgid] =  $rnd;
        $hh = $rnd->getHouseholds();
        $cp = $rnd->getCompleted();
        $tg = $rnd->getTarget();
        $rg = $rnd->getRoadgroups();
        $bnd = $rnd->getGeodata();
        $rndgroups[$rggrp]["group"]->addHouseholds($hh);
        $rndgroups[$rggrp]["group"]->addCompleted($cp);
        $rndgroups[$rggrp]["group"]->addTarget($tg);
        $rndgroups[$rggrp]["group"]->addRoadgroups($rg);
        $rndgroups[$rggrp]["group"]->expandBounds($bnd);
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"]->addHouseholds($hh);
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"]->addCompleted($cp);
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"]->addTarget($tg);
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"]->addRoadgroups($rg);
        $rndgroups[$rggrp]["children"][$rgsubgrp]["group"]->expandBounds($bnd);
    }
    return $rndgroups;
  }



  public function makeroadgrouptree($dvyid,$rglist)
  {
    $rggroups= array();
    foreach($rglist as $key=>$rg)
    {
      $rgid = $rg["roadgroupid"];
      $rggrp = $rg["rggroupid"];
      $rgsubgrp = $rg["rgsubgroupid"];
      if(! array_key_exists ( $rggrp , $rggroups ) )
      {
        $anobj = new Rggroup();
        $oldobj = $this->em->getRepository('App:Rggroup')->findOne($rggrp);
        $anobj->copy($oldobj);
        $rggroups[$rggrp]["children"] = array();
        $rggroups[$rggrp]["group"] =  $anobj;
        $rggroups[$rggrp]["group"]->setHouseholds(0);
        $rggroups[$rggrp]["group"]->setRoadgroups(0);
        $rggroups[$rggrp]["group"]->setElectors(0);
      }
      if(! array_key_exists ( $rgsubgrp , $rggroups[$rggrp]["children"] ) )
      {
        $asubobj = new Rgsubgroup();
        $oldsubobj = $this->em->getRepository('App:Rgsubgroup')->findOne($rgsubgrp);
        $asubobj->copy($oldsubobj);
        $rggroups[$rggrp]["children"][$rgsubgrp]["children"] = array();
        $rggroups[$rggrp]["children"][$rgsubgrp]["group"] = $asubobj;
      }
      // if(! array_key_exists ( $rgid ,  $rggroups[$rggrp]["children"][$rgsubgrp]["children"]) )
      {
        $thisrg =  $this->em->getRepository('App:Roadgroup')->findOne($rgid,$this->rgyear);
        if($thisrg)
        {
          $rggroups[$rggrp]["children"][$rgsubgrp]["children"][$rgid] =  $thisrg;
          $hh = $thisrg->getHouseholds();
          $rggroups[$rggrp]["group"]->addHouseholds($thisrg->getHouseholds());
          $rggroups[$rggrp]["group"]->addRoadgroups(1);
          $rggroups[$rggrp]["group"]->addElectors($thisrg->getElectors());
          $rggroups[$rggrp]["children"][$rgsubgrp]["group"]->addHouseholds($thisrg->getHouseholds());
          $rggroups[$rggrp]["children"][$rgsubgrp]["group"]->addRoadgroups(1);
          $rggroups[$rggrp]["children"][$rgsubgrp]["group"]->addElectors($thisrg->getElectors());
        }
      }
    }
    return $rggroups;
  }


  public function makebounds($roundstree)
  {
    $bounds =$this->mapserver->newbounds();
    foreach( $roundstree as &$agroup)
    {
      if( array_key_exists("children",$agroup))
      {
        $dbounds = $this->makebounds($agroup["children"]);
        $bounds = $this->mapserver->expandboundsobj($bounds, $dbounds);
        $agroup["group"]->setGeodata($bounds);
      }
     else
      {
        if( array_key_exists("roadgrouplist",$agroup))
        {
          foreach($agroup->roadgrouplist as $roadgroup)
          {
            $dbounds = $this->mapserver->makebounds($roadgroup["geodata"]);
            $bounds = $this->mapserver->expandboundsobj($bounds, $dbounds);

          }
          $agroup["group"]->setGeodata($bounds);
        }
      }
    }
   return $bounds;
  }

  public function makebounds_fromsubgroup($roundstree)
  {
    $bounds =$this->mapserver->newbounds();
    foreach( $roundstree as $around)
    {

            $dbounds = $this->mapserver->makebounds($around->getGeodata());
            $bounds = $this->mapserver->expandboundsobj($bounds, $dbounds);
    }
    return $bounds;
  }


  public function makebounds_roadgroups($roundstree)
  {
    $bounds =$this->mapserver->newbounds();
    foreach( $roundstree as &$agroup)
    {
      dump($agroup);
      if( array_key_exists("children",$agroup))
      {
        $dbounds = $this->makebounds_roadgroups($agroup["children"]);
        $bounds = $this->mapserver->expandbounds($bounds, $dbounds);
        $agroup["group"]->setGeodata($bounds);
      }
      else
      {
        if( array_key_exists("roadgrouplist",$agroup))
        {
          foreach($agroup["roadgrouplist"] as $roadgroup)
          {
            $dbounds = $this->mapserver->makebounds($roadgroup["geodata"]);
            $bounds = $this->mapserver->expandbounds($bounds, $dbounds);
          }
        }
        elseif(  property_exists($agroup,"Geodata"))
        {
          $dbounds = $this->mapserver->makebounds_fromarray($agroup->getGeodata());
          $bounds = $this->mapserver->expandbounds($bounds, $dbounds);
        }
      }
    }
    return $bounds;
  }




  public function updateCounts(&$delivery, $roundstree)
  {
    dump($delivery);
    $hh=0;
    $rgs= 0;
    foreach( $roundstree as $agroup)
    {

      $hh = $hh + $agroup["group"]->getHouseholds();
      $rgs =$rgs+ $agroup["group"]->getRoadgroups();
    }
    $delivery->Households =$hh;
    $delivery->Roadgroups = $rgs;
    return $delivery;

  }
}


