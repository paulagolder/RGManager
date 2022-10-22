<?php

namespace App\Entity;

use App\Repository\RggroupRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Geodata;

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

public function getGeodata()
{
 return  json_decode($this->Geodata,true);
}


public function getGeodata_json()
{
 return  $this->Geodata;
}

public function getGeodata_obj()
{
    $ngeodata = new Geodata;

 return  $ngeodata->loadGeodata($this->getGeodata());
}

public function setGeodata($text)
{
    $text_json = json_encode($text);
   $this->Geodata= $text_json;
}

 public function initGeodata()
 {
          $this->Geodata = json_encode( new Geodata());
  }



public function mergeGeodata_obj($geodata_obj)
{
   $g = $this->getGeodata_obj()->mergeGeodata_obj($geodata_obj);
   $this->setGeodata($g);
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
   $this->Target = $obj->getTarget();
   $this->Completed = $obj->getCompleted();
   $this->Roadgroups =$obj->getRoadgroups();
   $this->Streets =$obj->getStreets();
  // $this->Steps =$obj->getSteps();
   }else
   {
   $this->Name = $obj["name"];
   $this->Rndgroupid = $obj["Rggroupid"];
   $this->KML = $obj["KML"];
   $this->Households =$obj["Households"];
   $this->Target =$obj["Target"];
   $this->Completed =$obj["Completed"];
   $this->Roadgroups =$obj["Roadgroups"];
   $this->Streets =$obj["Streets"];
  // $this->Steps =$obj["Steps"];
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

