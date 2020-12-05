<?php

namespace App\Entity;

use App\Repository\PollingdistrictRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=PollingdistrictRepository::class)
 */
class Pollingdistrict
{
  /**
   * @ORM\Id
   *
   * @ORM\Column(name="pollingdistrictid",type="string")
   */
  private $PollingDistrictId;



  /**
   * @ORM\Column(name="households",type="integer", nullable=true)
   */
  private $Households;

  /**
   * @ORM\Column(name="electors",type="integer", nullable=true)
   */
  private $Electors;

  /**
   * @ORM\Column(name="kml",type="string", length=20, nullable=true)
   */
  private $KML;

  /**
   * @ORM\Column(name="minlat",type="float", nullable=true)
   */
  private $minlat;

  /**
   * @ORM\Column(name="midlat",type="float", nullable=true)
   */
  private $midlat;

  /**
   * @ORM\Column(name="maxlat",type="float", nullable=true)
   */
  private $maxlat;

  /**
   * @ORM\Column(name="minlong",type="float", nullable=true)
   */
  private $minlong;

  /**
   * @ORM\Column(name="midlong",type="float", nullable=true)
   */
  private $midlong;

  /**
   * @ORM\Column(name="maxlong",type="float", nullable=true)
   */
  private $maxlong;



  public function getPollingDistrictId()
   {
     return $this->PollingDistrictId;
   }

   public function setPollingDistrictId($ID): self
   {
     $this->PollingDistrictId = $ID;

     return $this;
   }



   public function getHouseholds(): ?int
   {
     return $this->Households;
   }

   public function setHouseholds(?int $Households): self
   {
     $this->Households = $Households;

     return $this;
   }

   public function getElectors(): ?int
   {
     return $this->Electors;
   }

   public function setElectors(?int $Electors): self
   {
     $this->Electors = $Electors;

     return $this;
   }


   public function getKML(): ?string
   {
     return $this->KML;
   }

   public function setKML(?string $KML): self
   {
     $this->KML = $KML;

     return $this;
   }

   public function getMinlat(): ?float
   {
     return $this->minlat;
   }

   public function setMinlat(?float $minlat): self
   {
     $this->minlat = $minlat;

     return $this;
   }

   public function getMidlat(): ?float
   {
     return $this->midlat;
   }

   public function setMidlat(?float $midlat): self
   {
     $this->midlat = $midlat;

     return $this;
   }

   public function getMaxlat(): ?float
   {
     return $this->maxlat;
   }

   public function setMaxlat(?float $maxlat): self
   {
     $this->maxlat = $maxlat;

     return $this;
   }

   public function getMinlong(): ?float
   {
     return $this->minlong;
   }

   public function setMinlong(?float $minlong): self
   {
     $this->minlong = $minlong;

     return $this;
   }

   public function getMidlong(): ?float
   {
     return $this->midlong;
   }

   public function setMidlong(?float $midlong): self
   {
     $this->midlong = $midlong;

     return $this;
   }

   public function getMaxlong(): ?float
   {
     return $this->maxlong;
   }

   public function setMaxlong(?float $maxlong): self
   {
     $this->maxlong = $maxlong;

     return $this;
   }




   public function getjson()
   {

     $kml= null;
   if($this->KML)
   {
     $kml =$this->KML;
   }
   else
   {

   }


   $str ="{";
   $str .=  '"pollingdistrictid":"'.$this->PollingDistrictId.'",';
   $str .=  '"name":"'.$this->PollingDistrictId.'",';
   $str .=  '"kml":"'.$kml.'",';
   $str .=  '"longitude":"'.$this->midlong.'",';
   $str .=  '"latitude":"'.$this->midlat.'"';
   $str .="}";
   return  $str;
   }

   public function makexml()
   {
    $streets =$this->streets;
     $xmlout = "";
     $xmlout .= "      <pollingdistrict PollingDistrictId='$this->PollingDistrictId' Name='$this->Name' Households='$this->Households' >\n  ";
     foreach ($streets as $astreet )
      {
        $xmlout .= $astreet->makexml();
      }
     $xmlout .= "      </pollingdistrict>\n";
     return $xmlout;
   }
}
