<?php

namespace App\Entity;

use App\Repository\RggroupRepository;
use Doctrine\ORM\Mapping as ORM;


class Rndgroup
{

    public $Rndgroupid;

    public $Name;

    public $KML;

    public $Households;

    public $Completed;

    public $Roadgroups;

    public $Target;

    public $Geodata;


    public function getKML(): ?string
    {
        return $this->KML;
    }

    public function setKML($text): self
    {
        $this->KML = $text;
        return $this;
    }





    public function getRndgroupid()
    {
        return $this->Rndgroupid;
    }

    public function setRndgroupid($ID): self
    {
        $this->Rndgroupid = $ID;
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
        $this->Households =  $this->Households + $number;

        return $this;
    }


      public function getCompleted()
    {
        return $this->Completed;
    }

    public function setCompleted($number): self
    {
        $this->Completed = $number;

        return $this;
    }

     public function addCompleted($number): self
    {
        $this->Completed += $number;

        return $this;
    }


     public function getRoadgroups()
    {
        return $this->Roadgroups;
    }

    public function setRoadgroups($number): self
    {
        $this->Roadgroups = $number;

        return $this;
    }

     public function addRoadgroups($number): self
    {
        $this->Roadgroups =  $this->Roadgroups + $number;

        return $this;
    }


       public function getTarget()
    {
        return $this->Target;
    }

    public function setTarget($number): self
    {
        $this->Target = $number;

        return $this;
    }

     public function addTarget($number): self
    {
        $this->Target += $number;

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

     public function expandbounds($bounds_str)
     {
       if (!$bounds_str) return ;
     $bounds =  json_decode($bounds_str);
     $thisbounds= $this->getGeodata();
      if(!array_key_exists("minlat",  $this->getGeodata())) return;
          if(!array_key_exists("minlat", $bounds)) return;
        if($thisbounds["minlat"] === null)  $thisbounds["minlat"] =  $bounds->minlat;
        if($thisbounds["maxlong"] === null)  $thisbounds["maxlong"] =  $bounds->maxlong;
        if(($bounds->minlat !== null) && ($thisbounds["minlat"] >  $bounds->minlat)) $thisbounds["minlat"] = $bounds->minlat;
        if(($bounds->maxlong !== null) && ($thisbounds["maxlong"] <  $bounds->maxlong)) $thisbounds["maxlong"] = $bounds->maxlong;
         if($thisbounds["maxlat"] === null)  $thisbounds["maxlat"] =  $bounds->maxlat;
        if($thisbounds["minlong"] === null)  $thisbounds["minlong"] =  $bounds->minlong;
      if(($bounds->maxlat !== null) && ( $thisbounds["maxlat"] < $bounds->maxlat))  $thisbounds["maxlat"]= $bounds->maxlat;
      if(($bounds->minlong !== null) && ( $thisbounds["minlong"] > $bounds->minlong))  $thisbounds["minlong"] = $bounds->minlong;
      $this->setGeodata($thisbounds);
     }

  public function expandbounds_x($bounds)
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

     public function getBounds()
     {

        return $this->getGeodata();

     }


   public function getjson()
   {
   $kml="";

   $str ="{";
   $str .=  '"name":"'.$this->Name.'",';
   $str .=  '"rndgroupid":"'.$this->Rndgroupid.'",';
   $str .=  '"kml":"'.$kml.'" ';
   $str .="}";
   return  $str;
   }

   public  function copy($obj)
   {
   if(is_null($obj)) return;
   if(is_object($obj))
   {
   $this->Name = $obj->getName();
   $this->Rndgroupid = $obj->getRggroupid();
   $this->KML = $obj->getKML();
   $this->Households =$obj->getHouseholds();
   $this->Target = 0;
    $this->Completed = 0;
    $this->Roadgroups =0;
   }else
   {
   $this->Name = $obj["name"];
   $this->Rndgroupid = $obj["Rggroupid"];
   $this->KML = $obj["KML"];
   $this->Households =$obj["Households"];
   $this->Target =$obj["Target"];
    $this->Completed =$obj["Completed"];
     $this->Roadgroups =$obj["Roadgroups"];
   }
   }

   public function makexml()
   {
     $subgroups=$this->subgroups;
     $xmlout = "";
     $xmlout .= "  <rggroup Rndgroupid='$this->Rndgroupid' Name='$this->Name' Households='$this->Households' KML='$this->KML' Bounds='$this->getGeodata()' >\n  ";
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
     $csvout  .= "$this->Rndgroupid: $this->Name,,,$this->Households \n  ";
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

