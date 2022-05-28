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
   * @ORM\Column(name="roadgroups",type="integer",  nullable=true)
   */
  private $Roadgroups;


    /**
   * @ORM\Column(name="streets",type="integer",  nullable=true)
   */
  private $Streets;

   /**
   * @ORM\Column(name="geodata",type="string",  nullable=true)
   */
  private $Geodata;


      /**
   * @ORM\Column(name="PLVW",type="integer",  nullable=true)
   */
  private $PLVW;
  /**
   *
   *                /**
   * @ORM\Column(name="PLVN",type="integer",  nullable=true)
   */
   private $PLVN;


  private $Completed;
  private $Target;
  private $KML;


      public function getPLVW()
    {
      return $this->PLVW;
    }

    public function setPLVW($number): self
    {
      $this->PLVW = $number;
      return $this;
    }


    public function getPLVN()
    {
      return $this->PLVN;
    }

    public function setPLVN($number): self
    {
      $this->PLVN = $number;
      return $this;
    }

     public function getKML(): ?string
    {
        return $this->KML;
    }

    public function setKML($text): self
    {
        $this->KML = $text;
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
        $this->Roadgroups += $number;

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

     public function addElectors($number): self
    {
        $this->Electors += $number;

        return $this;
    }

  public function getStreets()
   {
     return $this->Streets;
   }

   public function setStreets($number): self
   {
     $this->Streets = $number;

     return $this;
   }

     public function addStreets($number): self
    {
        $this->Streets += $number;

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

public function getGeodata_obj()
{
 $ngeodata = new Geodata;

 return  $ngeodata->loadGeodata($this->getGeodata());
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



   public  function copy($obj)
   {
   if(is_null($obj)) return;
   if(is_object($obj))
   {
   $this->Name = $obj->Name;
   $this->Rggroupid = $obj->Rggroupid;
     $this->Rgsubgroupid = $obj->Rgsubgroupid;
   //$this->KML = $obj->KML;
   $this->Households = 0;
   }else
   {
   $this->Name = $obj["name"];
   $this->Rggroupid = $obj["Rggroupid"];
     $this->Rgsubgroupid = $obj->Rgsubgroupid;
   //$this->KML = $obj["KML"];
   $this->Households = 0;
   }
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

