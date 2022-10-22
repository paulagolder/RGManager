<?php

namespace App\Repository;

use App\Entity\ProblemStreet;
use Doctrine\ORM\EntityRepository;



class xProblemStreetRepository  extends EntityRepository
{

    public function findAll()
    {

       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.name", "ASC");
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }






}
