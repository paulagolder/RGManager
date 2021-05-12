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
     * @ORM\Column(name="street",type="string", length=10)
     */
    private $Street;



    /**
     * @ORM\Id
     * @ORM\Column(name="part",type="string", length=40, nullable=true)
     */
    private $Part;


    /**
     * @ORM\Column(name="pd",type="string", length=10, nullable=true)
     */
    private $PD;


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
    if($this->Part)
    {
    return $this->Street."/".$this->Part;
    }
    else
        return $this->Street;
    }



    public function getStreet(): ?string
    {
        return $this->Ntreet;
    }

    public function setStreet(string $Name): self
    {
        $this->Street = $Name;

        return $this;
    }

    public function getPart(): ?string
    {
        return $this->Part;
    }

    public function setPart(?string $part): self
    {
        $this->Part = $part;
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

