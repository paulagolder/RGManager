<?php

namespace App\Entity;

use App\Repository\StreetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StreetRepository::class)
 */
class Street
{

    /**
     * @ORM\Id
     * @ORM\Column(name="seq",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $Seq;

    /**
     * @ORM\Column(name="name",type="string", length=10)
     */
    private $Name;



    /**
     * @ORM\Column(name="part",type="string", length=40, nullable=true)
     */
    private $Part;


      /**
     * @ORM\Column(name="qualifier",type="string", length=4)
     */
    private $Qualifier;



    /**
     * @ORM\Column(name="pd",type="string", length=10, nullable=true)
     */
    private $PD;

       /**
     * @ORM\Column(name="note",type="string", length=100, nullable=true)
     */
    private $Note;


    /**
     * @ORM\Column(name="households",type="integer",  nullable=true)
     */
    private $Households;


    /**
     * @ORM\Column(name="electors",type="integer",  nullable=true)
     */
    private $Electors;


   /**
     * @ORM\Column(name="geodata",type="string", length=200,  nullable=true)
     */
    private $Geodata;



      /**
     * @ORM\Column(name="path",type="text",    nullable=true)
     */
    private $Path;

    /**
     * @ORM\Column(name="updated",type="datetime",    nullable=true)
     */
    private $Updated;





    public function load($starray)
    {
      $this->Name = $starray["name"];
      $this->Households = $starray["households"];
      $this->Qualifier = $starray["qualifier"];
      $this->Note = $starray["note"];
      $this->PD= $starray["pd"];
      $this->Part= $starray["part"];
      $this->ELectors= $starray["electors"];
      $this->Geodata= $starray["geodata"];
      $this->Path= $starray["path"];
       $this->Updated= $starray["updated"];
    }


     public function getSeq()
    {
        return $this->Seq;
    }

    public function setSeq($number): self
    {
        $this->Seq = $number;
        return $this;
    }

    public function getStreetId()
    {
    if($this->Part)
    {
    return $this->Name."/".$this->Part;
    }
    else
        return $this->Name;
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

    public function getPart(): ?string
    {
        return $this->Part;
    }

    public function setPart(?string $part): self
    {
        $this->Part = $part;
        return $this;
    }

    public function getFullname()
    {
    if( is_Null($this->Part) or $this->Part =="")
    return $this->Name;
    else
      return $this->Name."/".$this->Part;
    }

     public function getQualifier(): ?string
    {
        return $this->Qualifier;
    }

    public function setQualifier(?string $part): self
    {
        $this->Qualifier= $part;
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




    public function getPD()
    {
        return $this->PD;
    }

    public function setPD(?string $text): self
    {
        $this->PD = $text;
        return $this;
    }

     public function getNote()
    {
        return $this->Note;
    }

    public function setNote($text): self
    {
        $this->Note = $text;
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




    public function getPath()
    {
      $text =  $this->Path;

       $text = "[".$text."]";
         $text = str_replace( ",null","",   $text);
            $text = str_replace( "null,","",   $text);
        $text = str_replace( "[[[","[[",   $text);
           $text = str_replace( "[[{","[{",   $text);
        $text = str_replace( "]]]","]]",   $text);
         $text = str_replace( "}]]","}]",   $text);

        if(strpos($text,"{")=== false)
        {
           $armarry = json_decode($text);
           $newpath = '[{"start":"1","end":"99","steps":'.json_encode($armarry).'}]';
          return $newpath;
        }
        else
        {
        //is object
         return $text;
        }

    }

    /*
    [{"start":x,"end":y,"steps":[[],[]]}]
    */

    public function getDecodedPath()
    {
        $text =  $this->getPath();
         return json_decode($text);
    }

    public function setPath($text): self
    {
        $text = "[".$text."]";
        $text = str_replace( "[[[","[[",   $text);
        $text = str_replace( "]]]","]]",   $text);
        $text = str_replace( "[[{","[{",   $text);
        $text = str_replace( "}]]","}]",   $text);
        $this->Path = $text;
        return $this;
    }

     public function fixPath()
    {
        $this->Path = $this->getPath();
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


    public function getjson()
    {
       $str ="{";
       $str .=  '"name":"'.$this->Name.'",';
       $str .=  '"part":"'.$this->Part.'",';
       if($this->getGeodata())
       {
       $str .=  '"longitude":"'.$this->getGeodata()['midlong'].'",';
       $str .=  '"latitude":"'.$this->getGeodata()['midlat'].'"';
       }else
       {
        $str .=  '"longitude":"45",';
       $str .=  '"latitude":"0"';
       }
       $str .="}";
       return  $str;
    }


    public function merge( $astreet)
    {
     if($this->Name == null) $this->Name = $astreet->Name;
     if($this->Part == null) $this->Part = $astreet->Part;
     if($this->Households == null) $this->Households = $astreet->Households;
     if($this->Electors == null) $this->Electors  = $astreet->Electors ;
     if($this->PD == null) $this->PD  = $astreet->PD ;
     if($this->Geodata == null) $this->Geodata  = $astreet->Geodata;
     if($this->Note)
     {
     if(!(str_contains($this->Note,$astreet->Note)))
     {
        $this->Note .= " ".$astreet->Note ;
     }
     }
     else
     {
        $this->Note = $astreet->Note ;
     }
    }

   public function makexml($inset)
   {
     $anote = htmlspecialchars($this->Note, ENT_QUOTES);
      $qual = htmlspecialchars($this->Qualifier, ENT_QUOTES);
     $xmlout = $inset."<street   Name='$this->Name' Households='$this->Households' Qualifier='$qual' Note='$anote'   />\n  ";
     return $xmlout;
   }




    public function getDistance()
   {
     if($this->getGeodata())
     {
      $dist = $this->getGeodata()["dist"];

       return $dist;
       }
       else
       return 0;

   }

     public function getSteps()
   {
     if($this->getGeodata())
     {
      $dist = $this->getGeodata()["steps"];

       return $dist;
       }
       else
       return 0;

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

  public function makeGeodata()
  {
  $steps = $this->getDecodedPath()[0]->steps;
  if($steps)
  {
  $this->setGeodata($this->make_geodata_steps($steps));
  }

  }


  public function make_geodata_steps($steps)
    {
      $geodata=[];
      $geodata["dist"]=-1;
      $geodata["maxlat"]=0;
      $geodata["midlat"]=0;
      $geodata["minlat"]=0;
      $geodata["maxlong"]=0;
      $geodata["midlong"]=0;
      $geodata["minlong"]=0;
      $dist = 0;
      $minlat=360;
      $minlong =360;
      $maxlat=-360;
      $maxlong=-306;
      $nsteps =0;
      $oldcoords = $steps[0];
     foreach($steps as $step)
     {
       $coords= $step;
         $lat = $coords[0];
         $long= $coords[1];
         $dist += $this->getDistanceBetweenTwoPoints($coords, $oldcoords);
         if($maxlat < $lat)$maxlat= $lat;
         if($maxlong < $long)$maxlong= $long;
         if($minlat > $lat)$minlat= $lat;
         if($minlong > $long)$minlong= $long;
         $oldcoords = $coords;
         $nsteps ++;
     }
      $geodata["dist"]=   intval($dist*1000)/1000;
      $geodata["maxlat"]=$maxlat;
      $geodata["midlat"]=($maxlat+$minlat)/2;
      $geodata["minlat"]=$minlat;
      $geodata["maxlong"]=$maxlong;
      $geodata["midlong"]=($minlong+$maxlong)/2;
      $geodata["minlong"]=$minlong;
      $geodata["steps"]=$nsteps;
      return  $geodata;
    }



  public function getDistanceBetweenTwoPoints($point1 , $point2)
  {
    // array of lat-long i.e  $point1 = [lat,long]
    $earthRadius = 6371;  // earth radius in km
    $point1Lat = $point1[0];
    $point2Lat =$point2[0];
    $deltaLat = deg2rad($point2Lat - $point1Lat);
    $point1Long =$point1[1];
    $point2Long =$point2[1];
    $deltaLong = deg2rad($point2Long - $point1Long);
    $a = sin($deltaLat/2) * sin($deltaLat/2) + cos(deg2rad($point1Lat)) * cos(deg2rad($point2Lat)) * sin($deltaLong/2) * sin($deltaLong/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    $distance = $earthRadius * $c;
    return $distance;    // in km
  }
}

