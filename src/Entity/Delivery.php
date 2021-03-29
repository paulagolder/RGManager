<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeliveryRepository::class)
 */
class Delivery
{
    /**
     * @ORM\Id
     * @ORM\Column(name="deliveryid",type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $DeliveryId;

    /**
     * @ORM\Column(name="name",type="string", length=50)
     */
    private $Name;

     /**
     * @ORM\Column(name="targetdate",type="string", length=50)
     */
    private $TargetDate;

    /**
     * @ORM\Column(name="district",type="string", length=20)
     */
    private $District;

        /**
     * @ORM\Column(name="seat",type="string", length=20)
     */
    private $Seat;

     /**
     * @ORM\Column(name="comment",type="string", length=50)
     */
    private $Comment;



    public function getDeliveryId()
    {
        return $this->DeliveryId;
    }

    public function setDeliveryId($ID): self
    {
        $this->DeliveryId = $ID;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Text): self
    {
        $this->Name = $Text;
        return $this;
    }

     public function getDistrict(): ?string
    {
        return $this->District;
    }

    public function setDistrict(string $Text): self
    {
        $this->District = $Text;
        return $this;
    }

     public function getSeat(): ?string
    {
        return $this->Seat;
    }

    public function setSeat(string $Text): self
    {
        $this->Seat = $Text;
        return $this;
    }

     public function getTargetDate(): ?string
    {
        return $this->TargetDate;
    }

    public function setTargetDate(string $text): self
    {
        $this->TargetDate = $text;
        return $this;
    }


      public function getComment(): ?string
    {
        return $this->Comment;
    }

    public function setComment(string $text): self
    {
        $this->Comment = $text;
        return $this;
    }


}

