<?php

namespace App\Entity;

use App\Repository\RoadgroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;



/**
 * @ORM\Entity(repositoryClass=RoadGroupRepository::class)
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
   * @ORM\Column(name="name",type="string", length=20)
   */
  private $Name;


  /**
   * @ORM\Column(name="subwardid",type="string")
   */
  private $SubwardId;


  /**
   * @ORM\Column(name="wardid",type="string")
   */
  private $WardId;

  /**
   * @ORM\Column(name="households",type="integer", nullable=true)
   */
  private $Households;

  /**
   * @ORM\Column(name="electors",type="integer", nullable=true)
   */
  private $Electors;

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

  public function getRoadgroupId()
   {
     return $this->RoadgroupId;
   }

   public function setRoadgroupId($ID): self
   {
     $this->RoadgroupId = $ID;

     return $this;
   }

   public function getSubwardId()
   {
     return $this->SubwardId;
   }

   public function setSubwardId($ID): self
   {
     $this->SubwardId = $ID;

     return $this;
   }



   public function getWardId()
   {
     return $this->WardId;
   }

   public function setWardId($ID): self
   {
     $this->WardId = $ID;

     return $this;
   }


   public function getName(): ?string
   {
     return $this->Name;
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

   public function setHouseholds(?int $Households): self
   {
     $this->Households = $Households;

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

   public function getKML(): ?string
   {
     return $this->KML;
   }

   public function setKML(?string $KML): self
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
     return $this->priority;
   }

   public function setPriority(?float $priority): self
   {
     $this->priority = $priority;

     return $this;
   }

   public function getPrioritygroup(): ?string
   {
     return $this->prioritygroup;
   }

   public function setPrioritygroup(?string $prioritygroup): self
   {
     $this->prioritygroup = $prioritygroup;

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

   public function getjson()
   {

     $kml= null;
   if($this->KML)
   {
     $kml =$this->KML;
   }
   else
   {
      //$kml = "mappath".$this->RoadgroupId.".kml";
   }


   $str ="{";
   $str .=  '"roadgroupid":"'.$this->RoadgroupId.'",';
   $str .=  '"name":"'.$this->Name.'",';
   $str .=  '"wardid":"'.$this->WardId.'",';
   $str .=  '"subwardid":"'.$this->SubwardId.'",';
   $str .=  '"kml":"'.$kml.'",';
   $str .=  '"longitude":"'.$this->midlong.'",';
   $str .=  '"latitude":"'.$this->midlat.'"';
   $str .="}";
   return  $str;
   }

   public function makexml()
   {
    $streets =$this->streets;
     $xmlout = "";
     $xmlout .= "      <roadgroup RoadgroupId='$this->RoadgroupId' Name='$this->Name' Households='$this->Households' >\n  ";
     foreach ($streets as $astreet )
      {
        $xmlout .= $astreet->makexml();
      }
     $xmlout .= "      </roadgroup>\n";
     return $xmlout;
   }
}
