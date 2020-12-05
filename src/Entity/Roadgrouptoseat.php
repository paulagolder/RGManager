<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeatRepository::class)
 */
class Roadgrouptoseat
{
    /**
     * @ORM\Id
     * @ORM\Column(name="seatid",type="string")
     */
    private $SeatId;



     /**
     * @ORM\Column(name="districtid",type="string", length=50)
     */
    private $RoadgroupId;


      /**
     * @ORM\Column(name="date",type="integer", length=50)
     */
    private $Date;





      public function getSeatId()
    {
        return $this->SeatId;
    }

    public function setSeatId($ID): self
    {
        $this->SeatId = $ID;
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

    public function getDate()
    {
        return $this->Date;
    }

    public function setDate($date): self
    {
        $this->Date = $date;
        return $this;
    }




}

