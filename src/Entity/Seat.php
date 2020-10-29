<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeatRepository::class)
 */
class Seat
{
    /**
     * @ORM\Id
     * @ORM\Column(name="seatid",type="string")
     */
    private $SeatId;

    /**
     * @ORM\Column(name="name",type="string", length=50)
     */
    private $Name;

     /**
     * @ORM\Column(name="districtid",type="string", length=50)
     */
    private $DistrictId;


      /**
     * @ORM\Column(name="date",type="integer", length=50)
     */
    private $Date;



      /**
     * @ORM\Column(name="electorate",type="integer", length=50)
     */
    private $Electorate;


      /**
     * @ORM\Column(name="households",type="integer", length=50)
     */
    private $Households;

      /**
     * @ORM\Column(name="seats",type="integer", length=50)
     */
    private $Seats;

      public function getSeatId()
    {
        return $this->SeatId;
    }

    public function setSeatId($ID): self
    {
        $this->SeatId = $ID;
        return $this;
    }


    public function getDistrictId()
    {
        return $this->DistrictId;
    }

    public function setDistrictId($ID): self
    {
        $this->DistrictId = $ID;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

  public function getHouseholds()
    {
        return $this->Households;
    }

    public function setHouseholds($number): self
    {
        $this->Households = $number;
        return $this;
    }

  public function getElectors()
    {
        return $this->Electorate;
    }

    public function setElectors($number): self
    {
        $this->Electorate = $number;
        return $this;
    }

     public function getSeats()
    {
        return $this->Seats;
    }

    public function setSeats($number): self
    {
        $this->Seats = $number;
        return $this;
    }


   public function getjson()
   {
   $kml="";

   $str ="{";
   $str .=  '"name":"'.$this->Name.'",';
   $str .=  '"seatid":"'.$this->SeatId.'",';
   $str .=  '"kml":"'.$kml.'",';
   $str .=  '"longitude":"-1.8304",';
   $str .=  '"latitude":"52.6854"';
   $str .="}";
   return  $str;
   }

   public function makexml()
   {
     $subwards=$this->subwards;
     $xmlout = "";
     $xmlout .= "  <ward WardId='$this->WardId' Name='$this->Ward' Households='$this->Households' >\n  ";
     foreach ($subwards as $asubward )
     {
     $xmlout .= $asubward->makexml();
     }
     $xmlout .= "  </ward>\n";
     return $xmlout;
   }
}

