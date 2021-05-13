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
     * @ORM\Column(name="latitude",type="string", length=20,  nullable=true)
     */
    private $Latitude;

    /**
     * @ORM\Column(name="longitude",type="string", length=20,   nullable=true)
     */
    private $Longitude;


      /**
     * @ORM\Column(name="path",type="text",    nullable=true)
     */
    private $Path;


    public function load($starray)
    {
      $this->Name = $starray["name"];
      $this->Households = $starray["households"];
      $this->Qualifier = $starray["qualifier"];
      $this->Note = $starray["note"];
      $this->PD= $starray["pd"];
      $this->Part= $starray["part"];
      $this->ELectors= $starray["electors"];
      $this->Latitude= $starray["latitude"];
      $this->Longitude= $starray["longitude"];
      $this->Path= $starray["path"];
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

    public function getjson()
    {
       $str ="{";
       $str .=  '"name":"'.$this->Name.'",';
       $str .=  '"part":"'.$this->Part.'",';
       $str .=  '"longitude":"'.$this->Longitude.'",';
       $str .=  '"latitude":"'.$this->Latitude.'"';
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
     if($this->Longitude == null) $this->Longitude  = $astreet->Longitude ;
     if($this->Latitude == null) $this->Latitude  = $astreet->Latitude ;
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

   public function makexml()
   {
     $anote = htmlspecialchars($this->Note, ENT_QUOTES);
      $qual = htmlspecialchars($this->Qualifier, ENT_QUOTES);
     $xmlout = "<street Name='$this->Name' Households='$this->Households' Qualifier='$qual'  />\n  ";
     return $xmlout;
   }


   public function countSteps()
   {
     $path = json_decode($this->getPath());
     $k=0;
     if($path)
     {

     foreach($path as $branch)
     {
        dump($branch);
       if(count($branch->steps)>0) $k++;
     }
       return $k;
       }
     else
       return 0;
   }
}

