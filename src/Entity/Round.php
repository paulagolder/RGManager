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
     * @ORM\Column(name="deliveries",type="integer")
     */
    public $Deliveries;

    public function getRoundId()
    {
        return $this->RoundId;
    }

    public function setRoundId($ID): self
    {
        $this->RoundId = $ID;
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
      public function getDeliveries()
    {
        return $this->Deliveries;
    }

    public function setDeliveries($number): self
    {
        $this->Deliveries = $number;
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

    public function setKML(string $Text): self
    {
        $this->KML = $Text;
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


   public function setValues_array($roadgroups)
   {
     $tothh= 0;
     $maxhh=0;
     $name="";
     dump($this);
      //$roadgroups =  $this->getDoctrine()->getRepository("App:RoundtoRoadgroup")->findRoadgroups($this->RoundId);
     foreach($roadgroups as $roadgroup)
     {
       // $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rg->getRoadgroupId(),$rgyear);
        $tothh += $roadgroup["households"];
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

   }

   public function setValues($roadgroups)
   {
     $tothh= 0;
     $maxhh=0;
     $name="";
     dump($this);
     foreach($roadgroups as $roadgroup)
     {
        $hh= $roadgroup->getHouseholds();
        $tothh += $hh;
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
    }
    $this->setHouseholds($tothh);
    $this->setName($name);

   }
}

