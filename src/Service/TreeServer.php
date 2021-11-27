<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

use  App\Entity\Rggroup;
use  App\Entity\Rgsubgroup;
use App\Entity\Delivery;
use App\Entity\Round;
use Doctrine\ORM\EntityManagerInterface;

class TreeServer
{

  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function maketree_array($rndlist)
  {
    $rggroups= array();
    dump($rndlist);
    foreach($rndlist as $key=>$rnd)
    {
      $rggrpid = $rnd["rggroupid"];
      $rgsubgrpid = $rnd["rgsubgroupid"];
      if(is_null($rggrpid ))
      {
        $rggrpid ="AllGroups";
      }
      if(! array_key_exists ( $rggrpid , $rggroups ) )
      {
        dump($rggrpid);
        $oldobj = $this->em->getRepository('App:Rggroup')->findOne($rggrpid);
        dump($oldobj);
        $anobj = new Rggroup();
        $anobj->setRggroupId($rggrpid);
        $anobj->copy($oldobj);
        $rggroups[$rggrpid]["children"] = array();
        $rggroups[$rggrpid]["group"] =  $anobj;
        $rggroups[$rggrpid]["group"]->setHouseholds(0);

      }
      if(is_null($rgsubgrpid ))
      {
        $rgsubgrpid ="AllSubGroups";
      }
      if(! array_key_exists ( $rgsubgrpid , $rggroups[$rggrpid]["children"] ) )
      {
        $asubobj = new Rgsubgroup();
        $oldsubobj = $this->em->getRepository('App:Rgsubgroup')->findOne($rgsubgrpid);
        $asubobj->copy($oldsubobj);
        $asubobj->setRgsubgroupId($rgsubgrpid);
        $rggroups[$rggrpid]["children"][$rgsubgrpid]["children"] = array();
        $rggroups[$rggrpid]["children"][$rgsubgrpid]["group"] = $asubobj;
      }
          $rggroups[$rggrpid]["children"][$rgsubgrpid]["children"][$key] =  $rnd;
          $hh = $rnd["households"];
          $rggroups[$rggrpid]["group"]->addHouseholds($hh);
    }
    return $rggroups;
  }


  public function maketree_object($rndlist)
  {
    $rggroups= array();
    foreach($rndlist as $key=>$rnd)
    {
      $rgid = $rnd->getRoadgroupId();
      $rggrp =  $rnd->getRggroupId();
      $rgsubgrp =  $rnd->getRgsubgroupId();
      if(! array_key_exists ( $rggrp , $rggroups ) )
      {
        $anobj = new Rggroup();
        $oldobj = $this->em->getRepository('App:Rggroup')->findOne($rggrp);
        dump($oldobj);
        $anobj->copy($oldobj);
        $rggroups[$rggrp]["children"] = array();
        $rggroups[$rggrp]["group"] =  $anobj;
        $rggroups[$rggrp]["group"]->setHouseholds(0);
        $rggroups[$rggrp]["group"]->setCompletions(0);
        $rggroups[$rggrp]["group"]->initGeodata();

      }
      if(! array_key_exists ( $rgsubgrp , $rggroups[$rggrp]["children"] ) )
      {

        $asubobj = new Rgsubgroup();
        $oldsubobj = $this->em->getRepository('App:Rgsubgroup')->findOne($rgsubgrp);
        dump($oldsubobj);
        $asubobj->copy($oldsubobj);
        $rggroups[$rggrp]["children"][$rgsubgrp]["children"] = array();
        $rggroups[$rggrp]["children"][$rgsubgrp]["group"] = $asubobj;
        $rggroups[$rggrp]["children"][$rgsubgrp]["group"]->setHouseholds(0);
        $rggroups[$rggrp]["children"][$rgsubgrp]["group"]->setCompletions(0);
        $rggroups[$rggrp]["children"][$rgsubgrp]["group"]->initGeodata();
      }
      $thisrg =  $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid,$this->rgyear);
      if($thisrg)
      {
        $rggroups[$rggrp]["children"][$rgsubgrp]["children"][$rgid] =  $thisrg;
        $hh = $thisrg->getHouseholds();
        $rggroups[$rggrp]["group"]->addHouseholds($hh);
      }

      $rggroups[$rggrp]["children"][$rgsubgrp]["group"]->addHouseholds($hh);

    }
    return $rggroups;
  }







}


