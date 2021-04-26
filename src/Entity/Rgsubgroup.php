<?php

namespace App\Entity;

use App\Repository\RgsubgroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RgsubgroupRepository::class)
 */
class Rgsubgroup
{
  /**
   * @ORM\Id
   * @ORM\Column(name="rgsubgroupid",type="string")
   */
  private $Rgsubgroupid;



  /**
   * @ORM\Column(name="name",type="string", length=50)
   */
  private $Name;


  /**
   * @ORM\Column(name="rggroupid",type="string")
   */
  private $Rggroupid;

  /**
   * @ORM\Column(name="households",type="integer",  nullable=true)
   */
  private $Households;

  private $Completions;
  /**
   * @ORM\Column(name="electors",type="integer",  nullable=true)
   */
  private $Electors;


  /**
   * @ORM\Column(name="roads",type="integer",  nullable=true)
   */
  private $Roads;


  /**
   * @ORM\Column(name="roadgroups",type="integer",  nullable=true)
   */
  private $roadgroups;



  private $Geodata;


  public function getRgsubgroupid()
   {
     return $this->Rgsubgroupid;
   }

   public function setRgsubgroupid($ID): self
   {
     $this->Rgsubgroupid = $ID;

     return $this;
   }

   public function getName()
   {
     return $this->Name;
   }

   public function setName($ID): self
   {
     $this->Name = $ID;

     return $this;
   }

   public function getRggroupid()
   {
     return $this->Rggroupid;
   }

   public function setRggroupid($ID): self
   {
     $this->Rggroupid = $ID;

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

   public function getRoads()
   {
     return $this->Roads;
   }

   public function setRoads($number): self
   {
     $this->Roads = $number;

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


   public function setRoadgrouplist($list): self
   {
     $this->Roadgrouplist = $list;
     return $this;
   }

   public function getRoadgrouplist()
   {
     return $this->Roadgrouplist;
   }



   public function getPriority(): ?float
   {
     return $this->Priority;
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
     $str .=  '"wardid":"'.$this->Rggroupid.'",';
     $str .=  '"subwardid":"'.$this->Rgsubgroupid.'",';
     $str .=  '"kml":"'.$kml.'" ';
     $str .="}";
     return  $str;
   }

   public function copy($obj)
   {
   $this->Name = $obj->Name;
   $this->Rggroupid = $obj->Rggroupid;
   $this->Rgsubgroupid = $obj->Rgsubgroupid;
   $this->Households = 0;
   }

   public function makexml()
   {
     $roadgroups =$this->roadgrouplist;
     $xmlout = "";
     $xmlout .= "    <rgsubgroup RgsubgroupId='$this->Rgsubgroupid' Name='$this->Name' Households='$this->Households' >\n  ";
     foreach ($roadgroups as $aroadgroup )
     {
        $xmlout .= $aroadgroup->makexml();
     }
     $xmlout .= "    </rgsubgroup>\n";
     return $xmlout;
   }


    public function makecsv()
   {
     $roadgroups =$this->roadgrouplist;
     $csvout = "";
      $subgrouplabel = "$this->Rgsubgroupid: $this->Name";
      $c =0;
     foreach ($roadgroups as $aroadgroup )
     {
     if($c==0)
         $csvout .= "   ,$subgrouplabel,".$aroadgroup->getRoadgroupId().": ".$aroadgroup->getName()." , ".$aroadgroup->getHouseholds()."\n  ";
     else
         $csvout .= "   ,,".$aroadgroup->getRoadgroupId().": ".$aroadgroup->getName()." ,". $aroadgroup->getHouseholds()." \n  ";
       $c++;
     }

     return  $csvout;
   }
}

