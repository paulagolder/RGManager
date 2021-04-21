<?php

namespace App\Entity;

use App\Repository\RoadgroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoadgroupRepository::class)
 */
class Roadgroup
{
  /**
   * @ORM\Id
   *
   * @ORM\Column(name="roadgroupid",type="string")
   */
  private $RoadgroupId;


  /**
   * @ORM\Id
   * @ORM\Column(name="year",type="integer")
   */
  private $Year;


  /**
   * @ORM\Column(name="name",type="string", length=20)
   */
  private $Name;


  /**
   * @ORM\Column(name="rgsubgroupid",type="string")
   */
  private $Rgsubgroupid;


  /**
   * @ORM\Column(name="rggroupid",type="string")
   */
  private $Rggroupid;

  /**
   * @ORM\Column(name="households",type="integer", nullable=true)
   */
  private $Households;


  /**
   * @ORM\Column(name="streets",type="integer", nullable=true)
   */
  private $Streets;


  /**
   * @ORM\Column(name="electors",type="integer", nullable=true)
   */
  private $Electors;

    /**
   * @ORM\Column(name="distance",type="float", nullable=true)
   */
  private $Distance;

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

  /**
   * @ORM\Column(name="Priority",type="float", nullable=true)
   */
  private $Priority;

  /**
   * @ORM\Column(name="prioritygroup",type="string", length=4, nullable=true)
   */
  private $Prioritygroup;

  /**
   * @ORM\Column(name="note",type="string", length=100, nullable=true)
   */
  private $Note;

  private $Bounds;

  public function getRoadgroupId()
   {
     return $this->RoadgroupId;
   }

   public function setRoadgroupId($ID): self
   {
     $this->RoadgroupId = $ID;
     return $this;
   }

   public function getRgsubgroupid()
   {
     return $this->Rgsubgroupid;
   }

   public function setRgsubgroupid($ID): self
   {
     $this->Rgsubgroupid = $ID;
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

    public function getYear()
   {
     return $this->Year;
   }

   public function setYear($yr): self
   {
     $this->Year = $yr;
     return $this;
   }


   public function getName(): ?string
   {
   if($this->endsWith($this->Name,"etc"))
   {
      return $this->Name;
   }
   else
     return $this->Name."..etc";
   }

   public function setName(string $name): self
   {
     $this->Name = $name;
     return $this;
   }

   public function getPrincipalRoad(): ?string
   {
     return $this->principalRoad;
   }

   public function setPrincipalRoad(?string $principalRoad): self
   {
     $this->principalRoad = $principalRoad;
     return $this;
   }

   public function getHouseholds(): ?int
   {
     return $this->Households;
   }

   public function setHouseholds(?int $number): self
   {
     $this->Households = $number;
     return $this;
   }

    public function getStreets(): ?int
   {
     return $this->Streets;
   }

   public function setStreets(?int $number): self
   {
     $this->Streets = $number;
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

   public function getDistance(): ?float
   {
     return $this->Distance;
   }

   public function setDistance(?float $miles): self
   {
     $this->Distance = $miles;
     return $this;
   }

   public function getKML()
   {
     return $this->KML;
   }

   public function setKML( $KML): self
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

   public function getPriority(): ?float
   {
     return $this->Priority;
   }

   public function setPriority(?float $priority): self
   {
     $this->Priority = $priority;
     return $this;
   }

   public function getPrioritygroup(): ?string
   {
     return $this->Prioritygroup;
   }

   public function setPrioritygroup(?string $prioritygroup): self
   {
     $this->Prioritygroup = $prioritygroup;
     return $this;
   }

   public function getNote(): ?string
   {
     return $this->Note;
   }

   public function setNote(?string $text): self
   {
     $this->Note = $text;
     return $this;
   }

   public function getNorthWest()
   {
      $nw = array();
      $nw["lat"] = $this->maxlat;
      $nw["long"] = $this->minlong;
      return $nw;
   }
   public function getSouthEast()
   {
       $se= array();
        $se["lat"] = $this->minlat;
        $se["long"] = $this->maxlong;
       return $se;
   }

   public function makeBounds()
   {
     $this->Bounds = array();
     $this->Bounds["nw"]=$this->getNorthwest();
     $this->Bounds["se"]=$this->getSouthEast();
   }

    public function getBounds()
   {
     $this->makeBounds();
     return $this->Bounds;
   }

    public function getBoundsStr()
   {

     $out = "{";
     $out .= '"se":{"lat" : '.$this->Bounds["se"]["lat"].',"long":'.$this->Bounds["se"]["long"].'},';
     $out .= '"nw":{"lat" : '.$this->Bounds["nw"]["lat"].',"long":'.$this->Bounds["nw"]["long"].'}}';
     return $out;
   }

   public function getjson()
   {
   if($this->midlat<40)
   {
    $this->midlat = ($this->maxlat + $this->minlat)/2;
    $this->midlong = ($this->minlong + $this->maxlong)/2;
   }
   $zoom = $this->getZoom();
   $str ="{";
   $str .=  '"roadgroupid":"'.$this->RoadgroupId.'",';
   $str .=  '"name":"'.$this->getName().'",';
   $str .=  '"rggroupid":"'.$this->Rggroupid.'",';
   $str .=  '"rgsubgroupid":"'.$this->Rgsubgroupid.'",';
   $str .=  '"kml":"'.$this->KML.'",';
   $str .=  '"longitude":"'.$this->midlong.'",';
   $str .=  '"latitude":"'.$this->midlat.'",';
   $str .=  '"maxlong":"'.$this->maxlong.'",';
   $str .=  '"minlong":"'.$this->minlong.'",';
   $str .=  '"maxlat":"'.$this->maxlat.'",';
   $str .=  '"minlat":"'.$this->minlat.'",';
   $str .=  '"zoom":"'.$zoom.'"';
   $str .="}";
   return  $str;
   }

   public function getZoom()
   {
     $zoom = ($this->maxlat - $this->minlat)/0.000050;
     return $zoom;
   }

   public function makexml()
   {
     $this->makeBounds();
     $streets =$this->streets;

     $xmlout = "";
     $xmlout .= "      <roadgroup RoadgroupId='$this->RoadgroupId' Name='".$this->getName()."' KML='$this->KML' Households='$this->Households'  Bounds='".$this->getBoundsStr()."' >\n";
     foreach ($streets as $astreet )
     {
    // dump($astreet);
        $xmlout .="        ".$astreet->makexml();
     }
     $xmlout .= "      </roadgroup>\n";
     return $xmlout;
   }

   function endsWith( $haystack, $needle )
   {
    $length = strlen( $needle );
    if( !$length )
    {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
   }

    public function makecsv()
   {
     $streets =$this->streets;
     $csvout = "";
      $csvout .= "   ,,$this->RoadgroupId:".$this->getName()." , $this->Households \n  ";
     foreach ($streets as $astreet )
     {
    // dump($astreet);
    //    $xmlout .="        ".$astreet->makexml();
     }

     return  $csvout;
   }

}
