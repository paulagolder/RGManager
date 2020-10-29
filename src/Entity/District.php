<?php

namespace App\Entity;

use App\Repository\DistrictRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DistrictRepository::class)
 */
class District
{
    /**
     * @ORM\Id
     * @ORM\Column(name="districtid",type="string")
     */
    private $DistrictId;

    /**
     * @ORM\Column(name="name",type="string", length=50)
     */
    private $Name;

    public function getDistrictId()
    {
        return $this->DistrictId;
    }

    public function setDistrictId($ID): self
    {
        $this->DistrictId = $ID;
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

   public function getjson()
   {
   $kml="";

   $str ="{";
   $str .=  '"name":"'.$this->Name.'",';
   $str .=  '"districtid":"'.$this->DistrictId.'",';
   $str .=  '"kml":"'.$kml.'",';
   $str .=  '"longitude":"-1.8304",';
   $str .=  '"latitude":"52.6854 "';
   $str .="}";
   return  $str;
   }

   public function makexml()
   {
     $subwards=$this->subwards;
     $xmlout = "";
     $xmlout .= "  <ward WardId='$this->WardId' Name='$this->Ward' Households='$this->Households' >\n  ";
     foreach ($subwards as $asubward )
     {
     $xmlout .= $asubward->makexml();
     }
     $xmlout .= "  </ward>\n";
     return $xmlout;
   }
}

