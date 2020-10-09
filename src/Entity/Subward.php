<?php

namespace App\Entity;

use App\Repository\SubwardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubwardRepository::class)
 */
class Subward
{
    /**
     * @ORM\Id
     * @ORM\Column(name="subwardid",type="string")
     */
    private $SubwardId;



    /**
     * @ORM\Column(name="subward",type="string", length=50)
     */
    private $Subward;


 /**
     * @ORM\Column(name="wardid",type="string")
     */
    private $WardId;

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

    public function getSubwardId()
    {
        return $this->SubwardId;
    }

    public function setSubwardId($ID): self
    {
        $this->SubwardId = $ID;

        return $this;
    }

      public function getSubward()
    {
        return $this->Subward;
    }

    public function setSubward($ID): self
    {
        $this->Subward = $ID;

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


  // $kml = "http://lerot.org/RoadGroups/maps/".$this->RoadgroupId.".kml";
   $kml="";

   $str ="{";
   $str .=  '"name":"'.$this->Subward.'",';
   $str .=  '"wardid":"'.$this->WardId.'",';
   $str .=  '"subwardid":"'.$this->SubwardId.'",';
   $str .=  '"kml":"'.$kml.'",';
   $str .=  '"longitude":"'.$this->Longitude.'",';
   $str .=  '"latitude":"'.$this->Latitude.'"';
   $str .="}";
   return  $str;
   }
}

