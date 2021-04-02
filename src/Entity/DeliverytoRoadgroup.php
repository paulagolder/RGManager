<?php

namespace App\Entity;

use App\Repository\DeliverytoRoadgroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=DeliverytoRoadgroupRepository::class)
 * @ORM\Table(name="deliverytoroadgroup")
 */

class DeliverytoRoadgroup
{
    /**
     * @ORM\Id
     * @ORM\Column(name="deliveryid",type="string")
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
        * @ORM\Id
     * @ORM\Column(name="roadgroupid",type="string", length=50)
     */
    private $RoadgroupId;


      /**
     * @ORM\Column(name="issuedate",type="string", length=50)
     */
    private $IssueDate;

      /**
     * @ORM\Column(name="households",type="string", length=50)
     */
    private $Households;

     /**
     * @ORM\Column(name="kml",type="string", length=50)
     */
    private $Kml;


     /**
     * @ORM\Column(name="completed",type="string", length=50)
     */
    private $Completed;

     /**
     * @ORM\Column(name="agent",type="string", length=50)
     */
    private $Agent;


     /**
     * @ORM\Column(name="feedback",type="string", length=50)
     */
    private $Feedback;

     /**
     * @ORM\Column(name="instructions",type="string", length=50)
     */
    private $Instructions;



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

     public function getYear()
    {
        return $this->Year;
    }

    public function setYear($text): self
    {
        $this->Year = $text;
        return $this;
    }

    public function getIssueDate()
    {
        return $this->IssueDate;
    }

    public function setIssueDate($text): self
    {
        $this->IssueDate = $text;
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

     public function getKML()
    {
        return $this->Kml;
    }

    public function setKML($text): self
    {
        $this->Kml = $text;
        return $this;
    }


     public function getAgent()
    {
        return $this->Agent;
    }

    public function setAgent($text): self
    {
        $this->Agent = $text;
        return $this;
    }


  public function getCompleted()
    {
        return $this->Completed;
    }

    public function setCompleted($text): self
    {
        $this->Completed = $text;
        return $this;
    }

    public function getFeedback()
    {
        return $this->Feedback;
    }

    public function setFeedback($text): self
    {
        $this->Feedback = $text;
        return $this;
    }

    public function getInstructions()
    {
        return $this->Completed;
    }

    public function setinstructions($text): self
    {
        $this->Completed = $text;
        return $this;
    }



}

