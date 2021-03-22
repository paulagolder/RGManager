<?php

namespace App\Entity;

use App\Repository\RggroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RggroupRepository::class)
 */
class Rggroup
{
    /**
     * @ORM\Id
     * @ORM\Column(name="rggroupid",type="string")
     */
    private $Rggroupid;



    /**
     * @ORM\Column(name="name",type="string", length=50)
     */
    private $Name;

    /**
     * @ORM\Column(name="kml",type="string", length=50)
     */
    private $KML;

     public function getKML(): ?string
    {
        return $this->KML;
    }

    public function setKML($text): self
    {
        $this->KML = $text;
        return $this;
    }


    /**
     * @ORM\Column(name="households",type="integer",  nullable=true)
     */
    private $Households;


    /**
     * @ORM\Column(name="electors",type="integer",  nullable=true)
     */
    private $Electors;







        /**
     * @ORM\Column(name="latitude",type="string", length=20,  nullable=true)
     */
    private $Latitude;

          /**
     * @ORM\Column(name="longitude",type="string", length=20,   nullable=true)
     */
    private $Longitude;

    public function getRggroupid()
    {
        return $this->Rggroupid;
    }

    public function setRggroupid($ID): self
    {
        $this->Rggroupid = $ID;
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
        return $this->Electors;
    }

    public function setElectors($number): self
    {
        $this->Electors = $number;

        return $this;
    }




    public function getLatitude()
    {
        return $this->Latitude;
    }

    public function setLatitude(?string $number): self
    {
        $this->Latitude = $number;

        return $this;
    }

      public function getLongitude()
    {
        return $this->Longitude;
    }

    public function setLongitude(?string $number): self
    {
        $this->Longitude = $number;

        return $this;
    }

   public function getjson()
   {
   $kml="";

   $str ="{";
   $str .=  '"name":"'.$this->Name.'",';
   $str .=  '"wardid":"'.$this->Rggroupid.'",';
   $str .=  '"kml":"'.$kml.'",';
   $str .=  '"longitude":"'.$this->Longitude.'",';
   $str .=  '"latitude":"'.$this->Latitude.'"';
   $str .="}";
   return  $str;
   }

   public function makexml()
   {
     $subwards=$this->subwards;
     $xmlout = "";
     $xmlout .= "  <rggroup RggroupId='$this->Rggroupid' Name='$this->Name' Households='$this->Households' KML='$this->KML' >\n  ";
     foreach ($subwards as $asubward )
     {
     $xmlout .= $asubward->makexml();
     }
     $xmlout .= "  </rggroup>\n";
     return $xmlout;
   }

    public function makecsv()
   {
     $subwards=$this->subwards;
     $csvout = "";
     $csvout  .= "$this->Rggroupid: $this->Name,,,$this->Households \n  ";
     foreach ($subwards as $asubward )
     {
     $csvout  .= "\n ";
     $csvout .= $asubward->makecsv();
      $csvout  .= "\n ";
     }
     $csvout  .= "\n";
     return $csvout;
   }
}

