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


   private $Completions;




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

    public function addHouseholds($number): self
    {
        $this->Households += $number;

        return $this;
    }


      public function getCompletions()
    {
        return $this->Completions;
    }

    public function setCompletions($number): self
    {
        $this->Completions = $number;

        return $this;
    }

     public function addCompletions($number): self
    {
        $this->Completions += $number;

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
   $str .=  '"rggroupid":"'.$this->Rggroupid.'",';
   $str .=  '"kml":"'.$kml.'",';
   $str .=  '"longitude":"'.$this->Longitude.'",';
   $str .=  '"latitude":"'.$this->Latitude.'"';
   $str .="}";
   return  $str;
   }

   public  function copy($obj)
   {
   $this->Name = $obj->Name;
   $this->Rggroupid = $obj->Rggroupid;
   $this->Households = 0;
   }

   public function makexml()
   {
     $subgroups=$this->subgroups;
     $xmlout = "";
     $xmlout .= "  <rggroup RggroupId='$this->Rggroupid' Name='$this->Name' Households='$this->Households' KML='$this->KML' >\n  ";
     foreach ($subgroups as $asubgroup )
     {
     $xmlout .= $asubgroup->makexml();
     }
     $xmlout .= "  </rggroup>\n";
     return $xmlout;
   }

    public function makecsv()
   {
     $subgroups=$this->subgroups;
     $csvout = "";
     $csvout  .= "$this->Rggroupid: $this->Name,,,$this->Households \n  ";
     foreach ($subgroups as $asubgroup )
     {
     $csvout  .= "\n ";
     $csvout .= $asubgroup->makecsv();
      $csvout  .= "\n ";
     }
     $csvout  .= "\n";
     return $csvout;
   }


}

