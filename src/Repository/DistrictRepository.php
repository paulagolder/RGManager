<?php

namespace App\Repository;

use App\Entity\District;
use Doctrine\ORM\EntityRepository;



class DistrictRepository  extends EntityRepository
{

    public function findone($drid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.DistrictId = :drid");
       $qb->setParameter('drid', $drid);
       $qb->orderBy("p.DistrictId", "ASC");
       $district =  $qb->getQuery()->getOneOrNullResult();
       return $district;
    }

    public function findgroup($drid)
    {
       $fnd = $drid."_%";
       $qb = $this->createQueryBuilder("p");
       $qb->where(  $qb->expr()->like('p.DistrictId', ':drid') );
       $qb->setParameter(':drid', $fnd);
       $qb->orderBy("p.DistrictId", "ASC");
       $districts =  $qb->getQuery()->getResult();
       return $districts;
    }


    public function findall()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Name", "ASC");
       $districts =  $qb->getQuery()->getResult();
       return $districts;
    }

    public function findAllIndexed()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.Display > 0");
       $qb->orderBy("p.Display", "ASC");
       $qb->orderBy("p.Name", "ASC");
       $districts =  $qb->getQuery()->getResult();
       return $districts;
    }


}
