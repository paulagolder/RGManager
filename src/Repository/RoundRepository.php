<?php

namespace App\Repository;

use App\Entity\Round;
use App\Entity\Roadgroup;
use App\Entity\RoundtoRoadgroup;
use Doctrine\ORM\EntityRepository;



class RoundRepository  extends EntityRepository
{

    public function findOne($rnid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.RoundId = :rnid");
       $qb->setParameter('rnid', $rnid);
       $Round =  $qb->getQuery()->getOneOrNullResult();
       return $Round;
    }

      public function xfindRounds($dvyid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.DeliveryId = :dvid");
       $qb->setParameter('dvid', $dvyid);
       $qb->orderBy("p.RoundId", "ASC");
       $Rounds =  $qb->getQuery()->getResult();
       return $Rounds;
    }

    public function findall()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Name", "ASC");
       $Rounds =  $qb->getQuery()->getResult();
       return $Rounds;
    }

    public function findcurrent()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Name", "ASC");
       $Rounds =  $qb->getQuery()->getResult();
       return $Rounds;
    }


  public function findRounds_array($dyid)
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
      $rndsarray[$rndid]["roadgrouplist"]=$roadgroups;
    }

    return $rndsarray;
  }

   public function findRounds($delivery)
  {
     $conn = $this->getEntityManager()->getConnection();
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.DeliveryId = :dvid");
       $qb->setParameter('dvid', $delivery->getDeliveryId());
       $qb->orderBy("p.RoundId", "ASC");
       $rounds =  $qb->getQuery()->getResult();
    return $rounds;
  }

    public function findSubgroupRounds($dvyid,$gpid,$sgpid)
  {
     $conn = $this->getEntityManager()->getConnection();
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.DeliveryId = :dvid");
       $qb->setParameter('dvid', $dvyid);
          $qb->andwhere("p.RggroupId = :rggroupid");
       $qb->setParameter('rggroupid', $gpid);
         $qb->andwhere("p.RgsubgroupId = :rgsubgroupid");
       $qb->setParameter('rgsubgroupid', $sgpid);
       $qb->orderBy("p.RoundId", "ASC");
       $rounds =  $qb->getQuery()->getResult();
    return $rounds;
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

  public function delete($rndid)
  {
    $qb = $this->createQueryBuilder("c")
    ->delete('App:Round', 'c')
    ->where('c.RoundId = :rndid ')
    ->setParameter(':rndid', $rndid);
    $query = $qb->getQuery();
    $query->execute();
  }

   public function deleteRounds($dvyid)
  {
    $qb = $this->createQueryBuilder("c")
    ->delete('App:Round', 'c')
    ->where('c.DeliveryId = :dvyid ')
    ->setParameter(':dvyid', $dvyid);
    $query = $qb->getQuery();
    $query->execute();
  }


     public function findCandidateRoundRoadgroups($round,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sqlpd = 'select p.pollingdistrictid  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pollingdistrictid and s.year = "'.$year.'" and s.district = "'.$round->getDistrict().'" ';
        if($round->getSeat())
         $sqlpd .= ' and s.seat = "'.$round->getSeat().'"';
        exit($sqlpd);

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


      public function getRoadgroups($rnid , $thisyear)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select rg.*  from roundtoroadgroup as r , roadgroup rg  where r.roundid ='.$rnid.' and r.roadgroupid = rg.roadgroupid order by rg.roadgroupid';
        $stmt = $conn->executeQuery($sql);
        $roadgroups= $stmt->fetchAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $rgsarray = array();
        foreach($roadgroups as $roadgroup)
        {

           $rgsarray[$roadgroup["roadgroupid"]] = $roadgroup;
        }
        return $rgsarray;
     }

}
