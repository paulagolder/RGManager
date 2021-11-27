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

   private $Geodata;



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



public function getGeodata_json()
{
 return  $this->Geodata;
}

public function getGeodata()
{
 return  json_decode($this->Geodata,true);
}

public function setGeodata($text)
{
  $text_json = json_encode($text);
   $this->Geodata= $text_json;
}








 public function initGeodata()
    {
          $geodata = array();
          $geodata["dist"]=-1;
          $geodata["maxlat"]=null;
          $geodata["midlat"]=null;
          $geodata["minlat"]=null;
          $geodata["maxlong"]=null;
          $geodata["midlong"]=null;
          $geodata["minlong"]=null;
          $this->Geodata = json_encode($geodata);
    }

     public function expandbounds($bounds)
     {
     if (!$bounds) return ;
     $thisbounds= $this->getGeodata();
      if(!array_key_exists("minlat",  $this->getGeodata())) return;
          if(!array_key_exists("minlat", $bounds)) return;
        if($thisbounds["minlat"] === null)  $thisbounds["minlat"] =  $bounds["minlat"];
        if($thisbounds["maxlong"] === null)  $thisbounds["maxlong"] =  $bounds["maxlong"];
        if(($bounds["minlat"] !== null) && ($thisbounds["minlat"] >  $bounds["minlat"])) $thisbounds["minlat"] = $bounds["minlat"];
        if(($bounds["maxlong"] !== null) && ($thisbounds["maxlong"] <  $bounds["maxlong"])) $thisbounds["maxlong"] = $bounds["maxlong"];
         if($thisbounds["maxlat"] === null)  $thisbounds["maxlat"] =  $bounds["maxlat"];
        if($thisbounds["minlong"] === null)  $thisbounds["minlong"] =  $bounds["minlong"];
      if(($bounds["maxlat"] !== null) && ( $thisbounds["maxlat"] < $bounds["maxlat"]))  $thisbounds["maxlat"]= $bounds["maxlat"];
      if(($bounds["minlong"] !== null) && ( $thisbounds["minlong"] > $bounds["minlong"]))  $thisbounds["minlong"] = $bounds["minlong"];
      $this->setGeodata($thisbounds);
     }


   public function getjson()
   {
   $kml="";

   $str ="{";
   $str .=  '"name":"'.$this->Name.'",';
   $str .=  '"rggroupid":"'.$this->Rggroupid.'",';
   $str .=  '"kml":"'.$kml.'" ';
   $str .="}";
   return  $str;
   }

   public  function copy($obj)
   {
   if(is_null($obj)) return;
   if(is_object($obj))
   {
   $this->Name = $obj->Name;
   $this->Rggroupid = $obj->Rggroupid;
   $this->KML = $obj->KML;
   $this->Households = 0;
   }else
   {
   $this->Name = $obj["name"];
   $this->Rggroupid = $obj["Rggroupid"];
   $this->KML = $obj["KML"];
   $this->Households = 0;
   }
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

