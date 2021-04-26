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

    /**
   * @ORM\Column(name="geodata",type="string", length=300, nullable=true)
   */
  private $Geodata;

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



   public function getjson()
   {
   $str ="{";
   $str .=  '"roadgroupid":"'.$this->RoadgroupId.'",';
   $str .=  '"name":"'.$this->getName().'",';
   $str .=  '"rggroupid":"'.$this->Rggroupid.'",';
   $str .=  '"rgsubgroupid":"'.$this->Rgsubgroupid.'",';
   $str .=  '"kml":"'.$this->KML.'",';
   $str .="}";
   return  $str;
   }



   public function makexml()
   {
     $streets =$this->streets;
     $xmlout = "";
     $xmlout .= "      <roadgroup RoadgroupId='$this->RoadgroupId' Name='".$this->getName()."' KML='$this->KML' Households='$this->Households'  Bounds='".$this->getGeodata_json()."' >\n";
     foreach ($streets as $astreet)
     {
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
