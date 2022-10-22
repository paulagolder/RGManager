<?php

namespace App\Repository;

use App\Entity\Street;
use Doctrine\ORM\EntityRepository;



class StreetRepository  extends EntityRepository
{

    public function findAll()
    {

       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Name", "ASC");
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }

      public function findProblems($filter)
    {

      $qb = $this->createQueryBuilder("p");
      if ($filter == "pd")
       {
         $qb->Where("p.PD IS NULL "); // NOT EMPTY
        }
        else
          if ($filter == "np")
       {
         $qb->Where("p.Path IS NULL "); // NOT EMPTY
        }
           else
          if ($filter == "ge")
       {
         $qb->Where("p.Geodata IS NULL "); // NOT EMPTY
        }
       $qb->orderBy("p.Name", "ASC");
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }

    public function findSplit()
    {
       $qb = $this->createQueryBuilder("s");
       $qb->select(['s.Seq','s.Name', 's.Part', 'count(s) as Count']);
       // $qb->from('App:Street', 's');
       $qb->groupBy('s.Name');
       $qb->Having('count(s)  > 1');
       $qb->orderBy("s.Name", "ASC");
       $streets =  $qb->getQuery()->getResult();
       $starray= [];
       foreach ($streets as $street)
       {
          $astreet = $this->findOnebySeq($street["Seq"]);
          $starry[]=$astreet;
       }
       return $starry;
    }

      public function findDuplicates()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT s.Seq as seq, LOWER( REPLACE( s.Name , " "  , "" )) as lname,  s.PdId as pd ,count(*) as Count  FROM street as s where 1 group by lname , pd having Count > 1 ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streets= $stmt->fetchAll();
       $starray= [];
       foreach ($streets as $street)
       {
        $astreet = $this->findOnebySeq($street["seq"]);
   $starry[]=$astreet;
       }
       return $starry;
    }

    public function findNew()
    {
        $qb = $this->createQueryBuilder("s");
        $qb->select(['s.Seq','s.Name', 's.Part', 's.PdId']);
        $qb->where('s.Qualifier  like :lk');
        $qb->setParameter('lk','%NEW%');
       $streets =  $qb->getQuery()->getResult();
       $starray= [];
       foreach ($streets as $street)
       {
        $astreet = $this->findOnebySeq($street["Seq"]);
   $starray[]=$astreet;
       }
       return $starray;
    }

    public function findOnebyName($stname,$stpart)
    {
      $sn= $stname;
      $sp = $stpart;
      $nl= "";
       $qb = $this->createQueryBuilder("p");
       $qb->Where('p.Name = :sn');
       $qb->andWhere('p.Part = :sp  or p.Part is null or p.Part = :nl ');
       $qb->setParameter('sn', $sn);
        $qb->setParameter('sp', $sp);
        $qb->setParameter('nl', $nl);
       $street =  $qb->getQuery()->getOneOrNullResult();
       return $street;
    }

     public function findAllbyNamePd($stname,$stpd)
    {
      $sn= $stname;
      $pd = $stpd;
      $nl= "";
       $qb = $this->createQueryBuilder("p");
       $qb->Where('p.Name LIKE :sn');
       $qb->andWhere('p.PD = :pd ');
       $qb->setParameter('sn', $sn);
        $qb->setParameter('pd', $pd);
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }


      public function findAllbyPd($pdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->Where('p.PdId = :pd ');
        $qb->setParameter('pd', $pdid);
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }

    public function findAllbyName($stname)
    {
      $stname = strtolower(str_replace(" ","",$stname));
     $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT s.Seq as seq, (REPLACE( LOWER(s.Name) , " "  , "" )) as lname,  s.pdid as pd  FROM street as s where  (REPLACE( LOWER(s.Name) , " "  , "" ))  = "'.$stname.'" ';
        //lname  like "%$stname%"';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streets= $stmt->fetchAll();
       $starry= [];
       foreach ($streets as $street)
       {
          $astreet = $this->findOnebySeq($street["seq"]);
          $starry[]=$astreet;
       }
      return $starry;
    }

    public function findOnebySeq($seq)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->Where('p.Seq = :sq');
       $qb->setParameter('sq', $seq);
       $street =  $qb->getQuery()->getOneOrNullResult();
       return $street;
    }

 public function findOnebyStreetId($streetid)
    {
      $sname = explode("/",$streetid);
      $sn = $sname[0];
      if(count($sname)== 2) $sp = $sname[1];
      else $sp = "";
       $qb = $this->createQueryBuilder("p");
       $qb->Where('p.Name = :sn');
       $qb->andWhere('p.Part = :sp  or p.Part is null  ');
       $qb->setParameter('sn', $sn);
        $qb->setParameter('sp', $sp);
       $street =  $qb->getQuery()->getOneOrNullResult();
       return $street;
    }

    public function findnamed($streetid,$year )
    {
     $sname = explode("/",$streetid);
      $sn = $sname[0];
      if(count($sname)== 2) $sp = $sname[1];
      else $sp = "";
      $yb = "";
      if($year != "*")
       $yb = ' and rg.year="'.$year.'"  ';

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT s.* FROM street as s left join roadgrouptostreet as rg on (s.name = rg.street and s.part = rg.part) where s.name = "'.$sn.'"  '.$yb.' ;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streets= $stmt->fetchAll();
        return $streets;
    }




    public function namesearch($streetname)
    {
       $streettoken = "%".$streetname."%";
       $qb = $this->createQueryBuilder("p");
       $qb->andWhere('p.Name like :stnm ');
       $qb->setParameter('stnm', $streettoken);
       $qb->orderBy("p.PdId", "ASC");
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }



     public function findLooseStreets_b($year)
     {
        $conn = $this->getEntityManager()->getConnection();
         $sql = 'select  st.*   from street as st  where concat (st.name,"-",st.part) not in ( SELECT concat (s.name,"-",s.part)  FROM street as s, roadgrouptostreet as rg WHERE s.name = rg.street and (s.part = rg.part or rg.part is null  or rg.part = "" ) and rg.year = "'.$year.'")  order by st.pdid , st.name ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streets= $stmt->fetchAll();
        return $streets;
      //  return null;
     }


       public function findLooseStreets($year)
     {
        $conn = $this->getEntityManager()->getConnection();
         $sql = 'SELECT s.* FROM `street` as s left OUTER join roadgrouptostreet as rs on s.seq = rs.streetid  and rs.year= '.$year.' WHERE rs.seq is null  order by s.pdid ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streets= $stmt->fetchAll();
        return $streets;
     }


         public function findLooseStreetsinPd($pdlist,$year)
     {
     dump($pdlist);

        $conn = $this->getEntityManager()->getConnection();
         $sql = 'SELECT s.* FROM `street` as s left OUTER join roadgrouptostreet as rs on s.seq = rs.streetid  and rs.year= '.$year.' WHERE rs.seq is null and FIND_IN_SET( pdid, '.$pdlist.') > 0     order by s.pdid ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streets= $stmt->fetchAll();
        return $streets;
     }



 public function findGroup($roadgroupid, $year)
     {

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT s.* FROM `street` as s JOIN roadgrouptostreet as r on s.seq = r.streetid WHERE r.roadgroupid = "'.$roadgroupid.'" and r.year = "'.$year.'" order by s.name ; ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streetars= $stmt->fetchAll();

        $streets = array();
        foreach( $streetars as $astreetar )
        {

         $astreet = new Street();
         $astreet->load($astreetar);
         $streets[] = $astreet;
     //    $streets[] =  json_decode(json_encode((object) $astreetar), FALSE);

        }
        return $streets;
     }


 public function findNoRoadgroup( $year)
     {

        $conn = $this->getEntityManager()->getConnection();
       $sqlx = 'SELECT s.*,r.* FROM `street` as s LEFT JOIN roadgrouptostreet as r on s.seq= r.streetid where r.roadgroupid is null ORDER BY `s`.`name` ASC ';

           $sql = 'SELECT s.*FROM `street` as s LEFT JOIN roadgrouptostreet as r on s.seq= r.streetid where ( r.roadgroupid is null or s.pdid is null)  ORDER BY `s`.`households` DESC ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streetars= $stmt->fetchAll();
        dump($streetars);
        $streets = array();
        foreach( $streetars as $astreetar )
        {

         $astreet = new Street();
         $astreet->load($astreetar);
         $astreet->roadgroupid = "NONE";
         dump($astreet);
         $streets[] = $astreet;

        }
        return $streets;
     }

}
