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





  private $minlat;


  private $maxlat;


  private $minlong;


  private $maxlong;

  private $Bounds;


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

   public function getLatitude()
   {
     return ($this->minlat + $this->maxlat)/2;
   }



   public function getLongitude()
   {
     return ($this->minlong + $this->maxlong)/2;
   }

   public function setLongitude(?string $number): self
   {
     $this->Longitude = $number;
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

     public function getMinlat(): ?float
   {
     return $this->minlat;
   }

   public function setMinlat(?float $minlat): self
   {
     $this->minlat = $minlat;
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

    public function setMinlong(?float $num): self
   {
     $this->mminlong = $num;
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

   public function getPriority(): ?float
   {
     return $this->Priority;
   }

    public function initBounds()
    {
      $this->Bounds["se"]["lat"] = null;
      $this->Bounds["nw"]["lat"] = null;
      $this->Bounds["se"]["long"] = null;
      $this->Bounds["nw"]["long"] = null;
    }

   public function getBounds()
   {
     return $this->Bounds;
   }

   public function getBoundsStr()
   {

     $out = "{";
     $out .= '"se":{"lat" : '.$this->Bounds["se"]["lat"].',"long":'.$this->Bounds["se"]["long"].'},';
     $out .= '"nw":{"lat" : '.$this->Bounds["nw"]["lat"].',"long":'.$this->Bounds["nw"]["long"].'}}';
     return $out;
   }



     public function expandbounds($bounds)
     {
        if($this->Bounds["se"]["lat"] === null)  $this->Bounds["se"]["lat"] =  $bounds["se"]["lat"];
        if($this->Bounds["se"]["long"] === null)  $this->Bounds["se"]["long"] =  $bounds["se"]["long"];
        if(($bounds["se"]["lat"] !== null) && ($this->Bounds["se"]["lat"] >  $bounds["se"]["lat"])) $this->Bounds["se"]["lat"] = $bounds["se"]["lat"];
        if(($bounds["se"]["long"] !== null) && ($this->Bounds["se"]["long"] <  $bounds["se"]["long"])) $this->Bounds["se"]["long"] = $bounds["se"]["long"];
         if($this->Bounds["nw"]["lat"] === null)  $this->Bounds["nw"]["lat"] =  $bounds["nw"]["lat"];
        if($this->Bounds["nw"]["long"] === null)  $this->Bounds["nw"]["long"] =  $bounds["nw"]["long"];
      if(($bounds["nw"]["lat"] !== null) && ( $this->Bounds["nw"]["lat"] < $bounds["nw"]["lat"]))  $this->Bounds["nw"]["lat"]= $bounds["nw"]["lat"];
      if(($bounds["nw"]["long"] !== null) && ( $this->Bounds["nw"]["long"] > $bounds["nw"]["long"]))  $this->Bounds["nw"]["long"] = $bounds["nw"]["long"];
     }

   public function getjson()
   {
     $kml="";
     $str ="{";
     $str .=  '"name":"'.$this->Name.'",';
     $str .=  '"wardid":"'.$this->Rggroupid.'",';
     $str .=  '"subwardid":"'.$this->Rgsubgroupid.'",';
     $str .=  '"kml":"'.$kml.'",';
     $str .=  '"longitude":"'.$this->getLongitude().'",';
     $str .=  '"latitude":"'.$this->getLatitude().'"';
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

