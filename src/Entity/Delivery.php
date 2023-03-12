<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeliveryRepository::class)
 */
class Delivery
{
    /**
     * @ORM\Id
     * @ORM\Column(name="deliveryid",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $DeliveryId;

    /**
     * @ORM\Column(name="name",type="string", length=50)
     */
    private $Name;


    /**
     * @ORM\Column(name="year",type="string", length=50)
     */
    private $Year;


    /**
     * @ORM\Column(name="targetdate",type="string", length=50)
     */
    private $TargetDate;

    /**
     * @ORM\Column(name="createdate",type="datetime")
     */
    private $CreateDate;

    /**
     * @ORM\Column(name="districtid",type="string", length=20)
     */
    private $DistrictId;

    /**
     * @ORM\Column(name="seatids",type="string", length=20)
     */
    private $SeatIds;

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
     * @ORM\Column(name="Completed",type="integer")
     */
    public $Completed;


    /**
     * @ORM\Column(name="target",type="integer")
     */
    public $Target;

    /**
     * @ORM\Column(name="roadgroups",type="integer")
     */
    public $Roadgroups;

    public $Geodata;

    public function getDeliveryId()
    {
        return $this->DeliveryId;
    }

    public function setDeliveryId($ID): self
    {
        $this->DeliveryId = $ID;
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
    public function getKML(): ?string
    {
        return $this->KML;
    }

    public function setKML(string $Text): self
    {
        $this->KML = $Text;
        return $this;
    }

    public function getDistrictId(): ?string
    {
        return $this->DistrictId;
    }

    public function setDistrictId(string $Text): self
    {
        $this->DistrictId = $Text;
        return $this;
    }

    public function getSeatIds(): ?string
    {
        return $this->SeatIds;
    }

    public function setSeatIds(string $Text): self
    {
        $this->SeatIds = $Text;
        return $this;
    }

    public function getYear(): ?string
    {
        return $this->Year;
    }

    public function setYear(string $text): self
    {
        $this->Year = $text;
        return $this;
    }

    public function getTargetDate(): ?string
    {
        return $this->TargetDate;
    }

    public function setTargetDate(string $text): self
    {
        $this->TargetDate = $text;
        return $this;
    }



    public function getCreateDate()
    {
        return $this->CreateDate;
    }

    public function setCreateDate($date): self
    {
        $this->CreateDate = $date;
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

    public function getRoadgroups()
    {
        return $this->Roadgroups;
    }

    public function setRoadgroups($num): self
    {
        $this->Roadgroups = $num;
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
        $str ="{";
        $str .=  '"name":"'.$this->Name.'",';
        $str .=  '"deliveryid":"'.$this->DeliveryId.'",';
        $str .=  '"kml":"'.$this->KML.'"';
        // $str .=  '"longitude":"'.$this->Longitude.'",';
        // $str .=  '"latitude":"'.$this->Latitude.'"';
        $str .="}";
        return  $str;
    }


    public function update($roundstree)
    {
        $tg=0;
        $cp=0;

        foreach($roundstree as $rndgroup)
        {
            $tg += $rndgroup["group"]->getTarget();
            $cp += $rndgroup["group"]->getCompleted();
        }
        $this->setTarget($tg);
        $this->setCompleted($cp);
    }

}

