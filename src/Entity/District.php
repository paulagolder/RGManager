<?php

namespace App\Entity;

use App\Repository\DistrictRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DistrictRepository::class)
 */
class District
{
    /**
     * @ORM\Id
     * @ORM\Column(name="districtid",type="string")
     */
    private $DistrictId;

    /**
     * @ORM\Column(name="groupid",type="string", length=50)
     */
    private $GroupId;


    /**
     * @ORM\Column(name="name",type="string", length=50)
     */
    private $Name;


    /**
     * @ORM\Column(name="level",type="string", length=50)
     */
    private $Level;

    /**
     * @ORM\Column(name="display",type="integer", length=50)
     */
    private $Display;

      /**
     * @ORM\Column(name="electors",type="integer", length=50)
     */
    private $Electors;


      /**
     * @ORM\Column(name="households",type="integer", length=50)
     */
    private $Households;

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


    public function getDistrictId()
    {
        return $this->DistrictId;
    }

    public function setDistrictId($ID): self
    {
        $this->DistrictId = $ID;
        return $this;
    }

    public function getGroupId()
    {
        return $this->GroupId;
    }

    public function setGroupId($ID): self
    {
        $this->GroupId = $ID;
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


    public function getDisplay()
    {
        return $this->Display;
    }

    public function setDisplay($number): self
    {
        $this->Display = $number;
        return $this;
    }

  public function getElectors()
    {
        return $this->Electors;
    }

    public function setElectors($number): self
    {
        $this->Electors = $number;
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



   public function xgetjson()
   {
   $str ="{";
   $str .=  '"name":"'.$this->Name.'",';
   $str .=  '"districtid":"'.$this->DistrictId.'",';
   $str .=  '"kml":"'.$this->KML.'",';
   $str .=  '"longitude":"-1.8304",';
   $str .=  '"latitude":"52.6854 "';
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

