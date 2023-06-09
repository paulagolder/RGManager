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
   * @ORM\Column(name="pdid",type="string")
   */
  private $PdId;

 /**
   *
   * @ORM\Column(name="districtid",type="string")
   */
  private $DistrictId;


 /**
   *
   * @ORM\Column(name="pdtag",type="string")
   */
  private $PdTag;




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
   * @ORM\Column(name="geodata",type="string", length=300, nullable=true)
   */
  private $Geodata;


  /**
   * @ORM\Column(name="name",type="string", length=40, nullable=true)
   */
  private $Name;



  public function getPdId()
   {
     return $this->PdId;
   }

   public function setPdId($ID): self
   {
     $this->PdId = $ID;

     return $this;
   }


     public function getPdTag()
   {
     return $this->PdTag;
   }

   public function setPdTag($text): self
   {
     $this->PdTag = $text;

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

    public function getDistrictId()
   {
     return $this->DistrictId;
   }

   public function setDistrictId($ID): self
   {
     $this->DistrictId = $ID;

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
