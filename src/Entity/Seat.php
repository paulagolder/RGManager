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
     * @ORM\Column(name="level",type="string", length=50)
     */
    private $Level;


      /**
     * @ORM\Column(name="date",type="integer", length=50)
     */
    private $Date;

    /**
     * @ORM\Column(name="kml",type="string", length=50)
     */
    private $KML;

       /**
   * @ORM\Column(name="geodata",type="string", length=300, nullable=true)
   */
  private $Geodata;


     public function getKML()
    {
        return $this->KML;
    }

    public function setKML( $text): self
    {
        $this->KML = $text;
        return $this;
    }

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


    public function getLevel(): ?string
    {
        return $this->Level;
    }

    public function setLevel(string $text): self
    {
        $this->Level = $text;
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


public function getGeodata_json()
{
 return  $this->Geodata;
}

public function getGeodata()
{
 return  json_decode($this->Geodata,true);
}

 public function setGeodata($text): self
    {
        $this->Geodata = json_encode($text);
        return $this;
    }

public function getGeodata_obj()
{
 $ngeodata = new Geodata;

 return  $ngeodata->loadGeodata($this->getGeodata());
}


   public function getjson()
   {
   $str ="{";
   $str .=  '"name":"'.$this->Name.'",';
   $str .=  '"seatid":"'.$this->SeatId.'",';
   $str .=  '"kml":"'.$this->KML.'",';
      $str .=  '"longitude":"0",';
   $str .=  '"latitude":"0"';
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

   public function __toString()
   {
        return $this->Name.";".$this->KML;
    }
}

