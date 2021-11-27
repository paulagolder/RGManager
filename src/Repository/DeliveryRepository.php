<?php

namespace App\Repository;

use App\Entity\Delivery;
use App\Entity\DeliverytoRoadgroup;
use Doctrine\ORM\EntityRepository;

class DeliveryRepository  extends EntityRepository
{

  public function findone($drid)
  {
    $qb = $this->createQueryBuilder("p");
    $qb->where("p.DeliveryId = :drid");
    $qb->setParameter('drid', $drid);
    $qb->orderBy("p.DeliveryId", "ASC");
    $Delivery =  $qb->getQuery()->getOneOrNullResult();
    return $Delivery;
  }

  public function findall()
  {
    $qb = $this->createQueryBuilder("p");
    $qb->orderBy("p.Name", "ASC");
    $Deliverys =  $qb->getQuery()->getResult();
    return $Deliverys;
  }

  public function findcurrent()
  {
    $qb = $this->createQueryBuilder("p");
    $qb->orderBy("p.CreateDate", "DESC");
    $Deliverys =  $qb->getQuery()->getResult();
    return $Deliverys;
  }

  public function findCandidateDeliveryRoadgroups($delivery,$year)
  {
    $conn = $this->getEntityManager()->getConnection();
    $sqlpd = 'select p.pollingdistrictid  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pollingdistrictid and s.year = "'.$year.'" and s.district = "'.$delivery->getDistrict().'" ';
    if($delivery->getSeat())
      $sqlpd .= ' and s.seat = "'.$delivery->getSeat().'"';
    //dump($sqlpd);
    $stmt = $conn->prepare($sqlpd);
    $stmt->execute();
    $pollingdistricts = $stmt->fetchAll();
    // dump($pollingdistricts);
    $pdlist = "";
    foreach($pollingdistricts  as $key => $value)
    {
      $pdlist  .= '"'.$value["pollingdistrictid"].'", ';
    }
    $pdlist .= '"xx"';
    //  dump($pdlist);
    $sql = 'select r.* from roadgroup as r where r.roadgroupid in (SELECT rs.roadgroupid FROM `roadgrouptostreet` as rs  WHERE rs.pd IN('.$pdlist.')   and rs.year = "'.$year.'") and r.year = "'.$year.'"';
    $stmt = $conn->executeQuery($sql);

    $roadgroups= $stmt->fetchAll();

    $rglist=[];
    foreach($roadgroups as $rg)
    {
      $rglist[$rg["roadgroupid"]]= $rg;
    }
    return $rglist;
  }


  public function findDeliveryRoadgroups($delivery,$year)
  {

    $conn = $this->getEntityManager()->getConnection();
    $sqlpd = 'select p.pollingdistrictid  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pollingdistrictid and s.year = "'.$year.'" and s.district = "'.$delivery->getDistrict().'" ';
    $stmt = $conn->prepare($sqlpd);

    $pollingdistricts = $stmt->fetchAll();

    dump($pollingdistricts);
    $pdarray = array();
    $sql = 'select r.* from roadgroup as r where r.roadgroupid in (SELECT rs.roadgroupid FROM `roadgrouptostreet` as rs  WHERE rs.pd IN(:pdlist)   and rs.year = "'.$year.'") and r.year = "'.$year.'"';

    $stmt = $conn->executeQuery(
      $sql,
      [
      'pdlist' => $pdarray
      ],
      [
      'pdlist' => \Doctrine\DBAL\Connection::PARAM_STR_ARRAY
      ]
      );
      $roadgroups= $stmt->fetchAll();
  }



  public function findRounds($dyid)
  {
    $conn = $this->getEntityManager()->getConnection();

    $sql = 'select r.* from round as r where r.deliveryid = '.$dyid;
    $stmt = $conn->executeQuery($sql);
    $rounds= $stmt->fetchAll();

    $rndsarray = array();
    foreach($rounds as $rnd)
    {
      $rndid= $rnd["roundid"];
      $rndsarray[$rndid] = $rnd;
      $sql = 'select rg.* from roundtoroadgroup as rtrg, roadgroup as rg where  rtrg.roundid = '.$rndid.'  and rtrg.roadgroupid = rg.roadgroupid ';
      $stmt = $conn->executeQuery($sql);
      $roadgroups= $stmt->fetchAll();
      $rndsarray[$rndid]["roadgroups"]=$roadgroups;
    }

    return $rndsarray;
  }

  public function getRound($dvyid,$rndid)
  {
    $conn = $this->getEntityManager()->getConnection();
    $queryBuilder = $conn->createQueryBuilder();
    $queryBuilder ->select('*');
    $queryBuilder ->from('round');
    $queryBuilder ->where('roundid = ?');
    $queryBuilder ->setParameter(0, $rndid);
    $queryBuilder ->where('deliveryid = ?');
    $queryBuilder ->setParameter(0, $dvyid);
    $stm = $queryBuilder->execute();
    $rounds = $stm->fetchAll();
    dump($rounds);
    if(count($rounds)>0)
      return $rounds[0];
    else
      return null;
  }

  public function findUnallocatedRoadgroups($dyid)
  {
    $conn = $this->getEntityManager()->getConnection();
    $sql = 'select r.* from roundtoroadgroup as r where  r.deliveryid = '.$dyid.'  and r.roundid = 0 ';
    $stmt = $conn->executeQuery($sql);
    $roadgroups= $stmt->fetchAll();
    return $roadgroups;
  }

  public function findAllocatedRoadgroups($dyid)
  {
    $conn = $this->getEntityManager()->getConnection();
    $sql = 'select r.* from roundtoroadgroup as r where  r.deliveryid = '.$dyid.'  and r.roundid > 0 ';
    $stmt = $conn->executeQuery($sql);
    $roadgroups= $stmt->fetchAll();
    return $roadgroups;
  }

  public function findRoadgroups($dvyid)
  {
    $conn = $this->getEntityManager()->getConnection();
    $sql = 'select rg.* from roundtoroadgroup as r , roadgroup as rg where  r.deliveryid = '.$dvyid.' and r.roadgroupid=rg.roadgroupid ';
    $stmt = $conn->executeQuery($sql);
    $roadgroups= $stmt->fetchAll();
    return $roadgroups;
  }

}
