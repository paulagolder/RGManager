<?php

namespace App\Entity;

use App\Repository\RggroupRepository;
use Doctrine\ORM\Mapping as ORM;


class Geodata
{

    public $maxlat;
    public $minlat;
    public $midlat;
    public $maxlong;
    public $minlong;
    public $midlong;
    public $dist;
    public $steps;
    public $streets;
    public $roadgroups;

     public function __construct()
     {
      $this->dist =0;
      $this->minlat=360;
      $this->minlong =360;
      $this->maxlat=-360;
      $this->maxlong=-360;
      $this->midlong=0;
      $this->midlat=0;
      $this->steps =0;
      $this->streets =0;
      $this->roadgroups =0;
     }


public function getGeodata_obj()
{
 return  $this->Geodata;
}

public function getGeodata_str()
{
 return  json_encode($this,true);
}

public function setGeodata_fromstr($text)
{
  $text_json = json_encode($text);
  $this->Geodata= $text_json;
}


public function makeGeodata($input)
{
  $geodata = new Geodata();

 if (!$input) return $geodata;
 if(is_object($input))
 {
   $geodata->dist = $input->dist;
   $geodata->minlat = $input->minlat;
   $geodata->maxlat = $input->maxlat;
   $geodata->maxlong = $input->maxlong;
   $geodata->minlong = $input->minlong;
      if(array_key_exists("steps",$input))
   $geodata->steps= $input->steps;
    if(array_key_exists("streets",$input))
        $geodata->streets =$input->streets;
   if(array_key_exists("roadgroups",$input))
   $geodata->roadgroups =$input->roadgroups;;

 }else
 {
      if(is_array($input))
      {
        $geodata = (object)$input;
      }
      else
        $geodata = json_decode($input);
  }
    $geodata->midlat =  ($geodata->minlat + $geodata->maxlat)/2;
   $geodata->midlong =  ($geodata->minlong + $geodata->maxlong)/2;
   return $geodata;
 }


public function isgeodata()
{


   if($this->maxlat > -360)   return true;
   else return false ;


}

public function loadGeodata($input)
{


 if (!$input) return $this;
 if(is_object($input))
 {
   $this->dist = $input->dist;
   $this->minlat = $input->minlat;
   $this->maxlat = $input->maxlat;
   $this->maxlong = $input->maxlong;
   $this->minlong = $input->minlong;
   $this->steps= $input->steps;
   $this->streets =$input->streets;
   $this->roadgroups =$input->roadgroups;;

 }else
 {
      if(is_array($input))
      {
         $this->dist = $input["dist"];
   $this->minlat = $input["minlat"];
   $this->maxlat = $input["maxlat"];
   $this->maxlong = $input["maxlong"];
   $this->minlong = $input["minlong"];
   if(array_key_exists("steps",$input))
       $this->steps= $input["steps"];
   if(array_key_exists("streets",$input))
       $this->streets =$input["streets"];
   if(array_key_exists("roadgroups",$input))
       $this->roadgroups =$input["roadgroups"];;
      }
      else
        return $this;
  }
    $this->midlat =  ($this->minlat + $this->maxlat)/2;
   $this->midlong =  ($this->minlong + $this->maxlong)/2;
   return $this;
 }




 public function mergeGeodata_obj($ingeodata)
  {

    if (!$ingeodata) return $this;
    if(is_object($ingeodata))
    {
        $ageodata= $ingeodata;
        //dump($rootgeodata);
        $rootgeodata = $this;

      if($rootgeodata->minlat === null)  $rootgeodata->minlat =  $ageodata->minlat;
      if($rootgeodata->maxlong === null)  $rootgeodata->maxlong =  $ageodata->maxlong;
      if(($ageodata->minlat !== null) && ($rootgeodata->minlat >  $ageodata->minlat)) $rootgeodata->minlat = $ageodata->minlat;
      if(($ageodata->maxlong !== null) && ($rootgeodata->maxlong <  $ageodata->maxlong)) $rootgeodata->maxlong = $ageodata->maxlong;
      if($rootgeodata->maxlat === null)  $rootgeodata->maxlat =  $ageodata->maxlat;
      if($rootgeodata->minlong === null)  $rootgeodata->minlong =  $ageodata->minlong;
      if(($ageodata->maxlat !== null) && ( $rootgeodata->maxlat < $ageodata->maxlat))  $rootgeodata->maxlat= $ageodata->maxlat;
      if(($ageodata->minlong !== null) && ( $rootgeodata->minlong > $ageodata->minlong))  $rootgeodata->minlong = $ageodata->minlong;

      $rootgeodata->dist =  $rootgeodata->dist +$ageodata->dist;
      $rootgeodata->steps =  $rootgeodata->steps +$ageodata->steps;
      $rootgeodata->streets =  $rootgeodata->streets +$ageodata->streets;
      $rootgeodata->roadgroups =  $rootgeodata->roadgroups+$ageodata->roadgroups;
       $rootgeodata->midlat =  ($rootgeodata->maxlat + $rootgeodata->minlat )/2;
         $rootgeodata->midlong =  ($rootgeodata->maxlong + $rootgeodata->minlong )/2;
         //dump($rootgeodata);
      return $rootgeodata;
      }else
      {
       throw new Exception('Not object');
      }

}





public function mergeGeodata_array($ingeodata)
  {

    if (!$ingeodata) return $this;

      if(is_array($ingeodata))
      {
      if($this->minlat === null)  $this->minlat =  $ingeodata["minlat"];
      if($this->maxlong === null)  $this->maxlong =  $ingeodata["maxlong"];
      if(($ingeodata["minlat"] !== null) && ($this->minlat >  $ingeodata["minlat"])) $this->minlat = $ingeodata["minlat"];
      if(($ingeodata["maxlong"] !== null) && ($this->maxlong <  $ingeodata["maxlong"])) $this->maxlong = $ingeodata["maxlong"];
      if($this->maxlat === null)  $this->maxlat =  $ingeodata["maxlat"];
      if($this->minlong === null)  $this->minlong =  $ingeodata["minlong"];
      if(($ingeodata["maxlat"] !== null) && ( $this->maxlat < $ingeodata["maxlat"]))  $this->maxlat= $ingeodata["maxlat"];
      if(($ingeodata["minlong"] !== null) && ( $this->minlong > $ingeodata["minlong"]))  $this->minlong = $ingeodata["minlong"];

      $this->dist =  $this->dist +$ingeodata["dist"];
      $this->steps =  $this->steps +$ingeodata["steps"];
      $this->streets =  $this->streets +$ingeodata["streets"];
      $this->roadgroups =  $this->roadgroups+$ingeodata["roadgroups"];
      $this->midlat =  ($this->maxlat + $this->minlat )/2;
      $this->midlong =  ($this->maxlong + $this->minlong )/2;
      return $this;
      }
      else
      {
       throw new Exception('Not array');
      }

}


  }



