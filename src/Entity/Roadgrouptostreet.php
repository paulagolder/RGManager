<?php

namespace App\Entity;

use App\Repository\RoadgrouptostreetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoadgrouptostreetRepository::class)
 */
class Roadgrouptostreet
{

  /**
     * @ORM\Id
     * @ORM\Column(name="seq",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $Seq;

/**
     *
     * @ORM\Column(name="streetid",type="integer")
     */
    private $StreetId;



     /**
     * @ORM\Column(name="roadgroupid",type="string", length=50)
     */
    private $RoadgroupId;


      /**
     * @ORM\Column(name="year",type="integer", length=4)
     */
    private $Year;



  public function getStreetId()
    {

        return $this->StreetId;
    }





    public function setStreetId($num): self
    {
        $this->StreetId = $num;

        return $this;
    }




    public function getRoadgroupId()
    {
        return $this->RoadgroupId;
    }

    public function setRoadgroupId($ID): self
    {
        $this->RoadgroupId = $ID;
        return $this;
    }

    public function getYear()
    {
        return $this->Year;
    }

    public function setYear($date): self
    {
        $this->Year= $date;
        return $this;
    }




}

