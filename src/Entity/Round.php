<?php

namespace App\Entity;

use App\Repository\RoundRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoundRepository::class)
 */
class Round
{
  /**
   * @ORM\Id
   * @ORM\Column(name="roundid",type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $RoundId;

  /**
   *
   * @ORM\Column(name="deliveryid",type="integer")
   */
  private $DeliveryId;


  /**
   * @ORM\Column(name="label",type="string", length=50)
   */
  private $Label;

  /**
   * @ORM\Column(name="name",type="string", length=50)
   */
  private $Name;

  /**
   * @ORM\Column(name="RgsubgroupId",type="string")
   */
  private $RgsubgroupId;


  /**
   * @ORM\Column(name="RggroupId",type="string")
   */
  private $RggroupId;



  /**
   * @ORM\Column(name="comment",type="string", length=50)
   */
  private $Comment;


  /**
   * @ORM\Column(name="kml",type="string", length=50)
   */
  public $KML;

  /**
   * @ORM\Column(name="households",type="integer")
   */
  public $Households;

  /**
   * @ORM\Column(name="distance",type="float", nullable=true)
   */
  private $Distance;


  /**
   * @ORM\Column(name="roadgroups",type="integer")
   */
  public $Roadgroups;

  /**
   * @ORM\Column(name="target",type="integer")
   */
  public $Target;

  /**
   * @ORM\Column(name="completed",type="integer")
   */
  public $Completed;


  /**
   * @ORM\Column(name="agent",type="string")
   */
  public $Agent;

  /**
   * @ORM\Column(name="geodata",type="string", length=300, nullable=true)
   */
  private $Geodata;

  /**
   * @ORM\Column(name="updated",type="datetime",    nullable=true)
   */
  private $Updated;


  public $Roadgrouplist;

  public function getRoundId()
   {
     return $this->RoundId;
   }

   public function setRoundId($ID): self
   {
     $this->RoundId = $ID;
     return $this;
   }

   public function getRoadgroups()
   {
     return $this->Roadgroups;
   }

   public function setRoadgroups($num): self
   {
     $this->Roadgroups = $num;
     return $this;
   }

   public function getRoadgrouplist()
   {
     return $this->Roadgrouplist;
   }

   public function setRoadgrouplist($arry): self
   {
     $this->Roadgrouplist = $arry;
     return $this;
   }

   public function getDeliveryId()
   {
     return $this->DeliveryId;
   }

   public function setDeliveryId($ID): self
   {
     $this->DeliveryId = $ID;
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

   public function getDistance(): ?float
   {
     return $this->Distance;
   }

   public function setDistance(?float $miles): self
   {
     $this->Distance = $miles;
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

   public function getCompleted()
   {
     return $this->Completed;
   }

   public function setCompleted($number): self
   {
     $this->Completed = $number;
     return $this;
   }


   public function getAgent()
   {
     return $this->Agent;
   }

   public function setAgent($text): self
   {
     $this->Agent = $text;
     return $this;
   }


   public function getName(): ?string
   {
     return $this->Name;
   }

   public function getSpacelessName(): ?string
   {

     $slname = str_replace(" ", "_",$this->Name);
   $slname = str_replace("__", "_",$slname);
   return $slname;
   }

   public function setName(string $Text): self
   {
     $this->Name = $Text;
     return $this;
   }


   public function getLabel(): ?string
   {
     return $this->Label;
   }



   public function setLabel(string $Text): self
   {
     $this->Label = $Text;
     return $this;
   }

   public function getRgsubgroupId()
   {
     return $this->RgsubgroupId;
   }

   public function setRgsubgroupId($ID): self
   {
     $this->RgsubgroupId = $ID;
     return $this;
   }

   public function getRggroupId()
   {
     return $this->RggroupId;
   }

   public function setRggroupId($ID): self
   {
     $this->RggroupId = $ID;
     return $this;
   }

   public function getKML(): ?string
   {
     return $this->KML;
   }

   public function setKML($Text): self
   {
     $this->KML = $Text;
     return $this;
   }

   public function getUpdated()
   {
     if($this->Updated)
   return $this->Updated;
   else
   {
     $format = 'Y-m-d H:i:s';
     return \DateTime::createFromFormat($format, '1944-07-08 00:00:01');
   }
   }

   public function setUpdated($dt)
   {
     $this->Updated = $dt;
     return $this;
   }


   public function getComment(): ?string
   {
     return $this->Comment;
   }

   public function setComment(string $text): self
   {
     $this->Comment = $text;
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

   public function setGeodata_json($json)
   {
      $this->Geodata= $json;
   }
   public function getjson()
   {
     ;

     $str ="{";
     $str .=  '"name":"'.$this->Name.'",';
     $str .=  '"roundid":"'.$this->RoundId.'",';
     $str .=  '"kml":"'.$this->KML.'"';
     // $str .=  '"longitude":"'.$this->Longitude.'",';
     // $str .=  '"latitude":"'.$this->Latitude.'"';
     $str .="}";
     return  $str;
   }


   public function setValues_array($roadgrouplist)
   {
     $tothh= 0;
     $maxhh=0;
     $name="";
     $Roadgroups = 0;
     dump($this);
   //$roadgroups =  $this->getDoctrine()->getRepository("App:RoundtoRoadgroup")->findRoadgroups($this->RoundId);
   $name = $roadgrouplist[array_key_first($roadgrouplist)]["name"];
   foreach($roadgrouplist as $roadgroup)
   {
     // $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rg->getRoadgroupId(),$rgyear);
     $tothh += $roadgroup["households"];
     $Roadgroups ++;
     if($roadgroup["households"]> $maxhh)
   {
     $name = $roadgroup["name"];
     $maxhh= $roadgroup["households"];
   }
   if(null == ($this->getRggroupId()))
   $this->setRggroupId($roadgroup["rggroupid"]);
   if($this->getRggroupId() != $roadgroup["rggroupid"] )
   $this->setRggroupId(null);
   if(null==$this->getRgsubgroupId())
   $this->setRgsubgroupId($roadgroup["rgsubgroupid"]);
   if($this->getRgsubgroupId() != $roadgroup["rgsubgroupid"] )
   $this->setRgsubgroupId(null);
   }
   $this->setHouseholds($tothh);
   $this->setName($name);
   $this->setRoadgroups($Roadgroups);
   }

   public function setValues($roadgroups)
   {
     $tothh= 0;
     $maxhh=0;
     $name="";
     $Roadgroups = 0;
     dump($this);
   foreach($roadgroups as $roadgroup)
   {
     $hh= $roadgroup->getHouseholds();
   $tothh += $hh;
   $Roadgroups ++;
   if($hh > $maxhh)
   {
     $name = $roadgroup->getName();
   $maxhh= $hh;
   }
   $rggid = $roadgroup->getRggroupid();
   if(null == ($this->getRggroupId()))
   $this->setRggroupId($rggid);
   if($this->getRggroupId() != $rggid)
   $this->setRggroupId(null);
   $rgsubgid = $roadgroup->getRgsubgroupid();
   if(null==$this->getRgsubgroupId())
   $this->setRgsubgroupId($rgsubgid);
   if($this->getRgsubgroupId() != $rgsubgid )
   $this->setRgsubgroupId(null);

   $kml = $roadgroup->getKML();
   }
   $this->setKML($kml);
   $this->setHouseholds($tothh);
   $this->setName($name);
   $this->setRoadgroups($Roadgroups);

   }

   function makeXML($inset="")
   {
     $geodata  = $this->getGeodata();
     $xmlout =$inset. "<round RoundId='$this->RoundId' Label='$this->Label' Name='$this->Name' Households='$this->Households'  KML='$this->KML' Bounds='$geodata' >\n  ";
     if($this->Roadgrouplist != null)
   {
     foreach($this->Roadgrouplist as $roadgroup)
   {
     $xmlout .= $roadgroup->makeXML($inset."  ");
   }
   }

   $xmlout .= $inset."</round> \n";
   return $xmlout;


   }

   public function getBounds()
   {
     $bounds = $this->getGeodata();
     if(! is_array($bounds))
        $bounds = JSON_decode($bounds);
     return $this->getGeodata();

   }

}

