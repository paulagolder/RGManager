<?php

namespace App\Entity;

use App\Repository\RoundtoRoadgroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=RoundtoRoadgroupRepository::class)
 * @ORM\Table(name="roundtoroadgroup")
 */

class RoundtoRoadgroup
{

 /**
     * @ORM\Id
     * @ORM\Column(name="seqid",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $SeqId;

    /**

     * @ORM\Column(name="roundid",type="integer")
     */
    private $RoundId;


     /**

     * @ORM\Column(name="deliveryid",type="integer")
      *
     */
    private $DeliveryId;


     /**
     * @ORM\Column(name="rggroupid",type="string", length=50)
     */
    private $RggroupId;

     /**
     * @ORM\Column(name="rgsubgroupid",type="string", length=50)
     */
    private $RgsubgroupId;

     /**

     * @ORM\Column(name="roadgroupid",type="string", length=50)
     */
    private $RoadgroupId;

      /**
     * @ORM\Column(name="name",type="string", length=100)
     */
    private $Name;


      /**
     * @ORM\Column(name="households",type="string", length=50)
     */
    private $Households;

    /**
     * @ORM\Column(name="deliveries",type="string", length=50)
     */
    private $Deliveries;


    public function getSeqId()
    {
        return $this->SeqId;
    }


    public function getRoundId()
    {
        return $this->RoundId;
    }

    public function setRoundId($ID): self
    {
        $this->RoundId = $ID;
        return $this;
    }


    public function getDeliveryId()
    {
        return $this->DeliveryId;
    }

    public function setDeliveryId($ID): self
    {
        $this->DeliveryId = $ID;
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

    public function getName()
    {
        return $this->Name;
    }

    public function setName($text): self
    {
        $this->Name = $text;
        return $this;
    }

     public function getRgsubgroupId()
    {
        return $this->RgsubgroupId;
    }

    public function setRgsubgroupId($ID): self
    {
        $this->RgsubgroupId = $ID;
        return $this;
    }


      public function getRggroupId()
    {
        return $this->RggroupId;
    }

    public function setRggroupId($ID): self
    {
        $this->RggroupId = $ID;
        return $this;
    }




     public function getHouseholds()
    {
        return $this->Households;
    }

    public function setHouseholds($num): self
    {
        $this->Households = $num;
        return $this;
    }

     public function getDeliveries()
    {
        return $this->Deliveries;
    }

    public function setDeliveries($num): self
    {
        $this->Deliveries = $num;
        return $this;
    }



  public function makecsv()
   {

     $csvout = "";
      $csvout .= "   ,,$this->RoadgroupId,". $this->getName()." ,". $this->Households .", ".$this->Deliveries."  \n";
     return  $csvout;
   }

}

