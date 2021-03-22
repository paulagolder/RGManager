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



  /**
   * @ORM\Column(name="latitude",type="string", length=20,  nullable=true)
   */
  private $Latitude;

  /**
   * @ORM\Column(name="longitude",type="string", length=20,   nullable=true)
   */
  private $Longitude;

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

   public function setRoadgrouplist($list): self
   {
     $this->Roadgrouplist = $list;
     return $this;
   }

   public function getRoadgrouplist()
   {
     return $this->Roadgrouplist;
   }


   public function getjson()
   {
     $kml="";
     $str ="{";
     $str .=  '"name":"'.$this->Name.'",';
     $str .=  '"wardid":"'.$this->Rggroupid.'",';
     $str .=  '"subwardid":"'.$this->Rgsubgroupid.'",';
     $str .=  '"kml":"'.$kml.'",';
     $str .=  '"longitude":"'.$this->Longitude.'",';
     $str .=  '"latitude":"'.$this->Latitude.'"';
     $str .="}";
     return  $str;
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

