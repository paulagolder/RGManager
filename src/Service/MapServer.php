<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class MapServer
{

  private  $mapath="";
  private  $rmaps=null;

  public function __construct(ParameterBagInterface $params)
  {
    $this->params = $params;
    $this->mappath =  $this->params->get('mappath');
    $this->maproot =  $this->params->get('maproot');
  }

  public function load($reload = false)
  {
    try
    {
      $session = new Session();
      if(session_status() === PHP_SESSION_NONE) session_start();
      $rmaps = $session->get('maps');
      if(!$rmaps or $reload)
      {
        $maps = scandir($this->maproot."/roadgroups");

        $session->set('maps', $maps);
      }
      $this->rmaps= $rmaps;
    } catch (FileException $e)
    {

    }
  }

  public function getmaps()
  {
    return $this->rmaps;
  }


  public function findmap($mapkey,$year="",$seatid="")
  {
    $mroot = $this->maproot."/roadgroups/";
    $mroot = str_replace("//","/",$mroot);
    $key1 = $mroot.$seatid."_".$mapkey."_".$year.".kml";
    $key0 = $mroot.$seatid."_".$mapkey."_".$year.".kml";
    $key = $seatid."_".$mapkey."_".$year.".kml";
    if(!file_exists($key1))
    {
      $key1 = $mroot.$mapkey."_".$year.".kml";
      $key = $mapkey."_".$year.".kml";
    }
    if(!file_exists($key1))
    {
      $key1 = $mroot.$mapkey."_".$year.".kml";
      $key =  $mapkey."_".$year.".kml";
    }
    if(!file_exists($key1))
    {
      $key1 = $mroot.$mapkey.".kml";
      $key =  $mapkey.".kml";
    }
    if(file_exists($key1))  return trim($key);
    else
    {
      echo '<script>console.log(" Not Found rg : '.$key0.'")</script>';
      return null;
    }

  }

  public function findseat($mapkey,$year="",$districtid="*")
  {

    $key1 = $this->maproot.$districtid."/".$districtid."_".$mapkey."_".$year.".kml";
    $key = $districtid."/".$districtid."_".$mapkey."_".$year.".kml";
    if(!file_exists($key1))
    {
      $key1 = $this->maproot.$districtid."/".$mapkey."_".$year.".kml";
      $key = $districtid."/".$mapkey."_".$year.".kml";
    }
    if(!file_exists($key1))
    {
      $key1 = $this->maproot.$districtid."/".$districtid."_".$mapkey.".kml";
      $key = $districtid."/".$districtid."_".$mapkey.".kml";
    }
    if(!file_exists($key1))
    {
      $key1 = $this->maproot.$districtid."/".$mapkey.".kml";
      $key = $districtid."/".$mapkey.".kml";
    }
    if(file_exists($key1))
    {
      echo '<script>console.log(" Found seat: '.$key.'")</script>';
      return $key;
    }
    else
    {
      echo '<script>console.log(" Not Found seat: '.$key.'")</script>';
      return null;
    }
  }


  public function finddistrict($districtid,$year="")
  {

    $key1 = $this->maproot.$districtid."/".$districtid."_".$year.".kml";
    $key = $districtid."/".$districtid."_".$year.".kml";
    if(!file_exists($key1))
    {
      $key1 = $this->maproot.$districtid."/".$districtid.".kml";
      $key = $districtid."/".$districtid.".kml";
    }
    if(file_exists($key1))
    {
      echo '<script>console.log(" Found district : '.$key.'")</script>';
      return $key;
    }
    else
    {
      echo '<script>console.log(" Not Found district : '.$key.'")</script>';
      return null;
    }
  }


  public function ismap($kml)
  {
    $k = array_search($kml,$this->rmaps);
    if(false !== $k)
    {
      return true;
    }
    else
      return false;
  }




  public function newbounds()
  {
    $bounds = array();
    $bounds["minlat"] = null;
    $bounds["maxlat"] = null;
    $bounds["maxlong"] = null;
    $bounds["minlong"] = null;
    return $bounds;
  }




  public function expandbounds($rootbounds, $bounds)
  {
    if (!$bounds) return $rootbounds;
    if(!array_key_exists("minlat", $rootbounds)) return $rootbounds;
    if(!array_key_exists("minlat", $bounds)) return $rootbounds;
    if($rootbounds["minlat"] === null)  $rootbounds["minlat"] =  $bounds["minlat"];
    if($rootbounds["maxlong"] === null)  $rootbounds["maxlong"] =  $bounds["maxlong"];
    if(($bounds["minlat"] !== null) && ($rootbounds["minlat"] >  $bounds["minlat"])) $rootbounds["minlat"] = $bounds["minlat"];
    if(($bounds["maxlong"] !== null) && ($rootbounds["maxlong"] <  $bounds["maxlong"])) $rootbounds["maxlong"] = $bounds["maxlong"];
    if($rootbounds["maxlat"] === null)  $rootbounds["maxlat"] =  $bounds["maxlat"];
    if($rootbounds["minlong"] === null)  $rootbounds["minlong"] =  $bounds["minlong"];
    if(($bounds["maxlat"] !== null) && ( $rootbounds["maxlat"] < $bounds["maxlat"]))  $rootbounds["maxlat"]= $bounds["maxlat"];
    if(($bounds["minlong"] !== null) && ( $rootbounds["minlong"] > $bounds["minlong"]))  $rootbounds["minlong"] = $bounds["minlong"];
    return $rootbounds;
  }


  public function getDistanceBetweenTwoPoints($point1 , $point2)
  {
    // array of lat-long i.e  $point1 = [lat,long]
    $earthRadius = 6371;  // earth radius in km
    $point1Lat = $point1[1];
    $point2Lat =$point2[1];
    $deltaLat = deg2rad($point2Lat - $point1Lat);
    $point1Long =$point1[0];
    $point2Long =$point2[0];
    $deltaLong = deg2rad($point2Long - $point1Long);
    $a = sin($deltaLat/2) * sin($deltaLat/2) + cos(deg2rad($point1Lat)) * cos(deg2rad($point2Lat)) * sin($deltaLong/2) * sin($deltaLong/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    $distance = $earthRadius * $c;
    return $distance;    // in km
  }




  public function loadRoute($path,$kmlfile)
  {
    $geodata=[];
    $geodata["dist"]=-1;
    $geodata["maxlat"]="0";
    $geodata["midlat"]="0";
    $geodata["minlat"]="0";
    $geodata["maxlong"]="0";
    $geodata["midlong"]="0";
    $geodata["minlong"]="0";
    if(!$kmlfile) return $geodata;
    $kmlpath =  $this->maproot.$path.$kmlfile;
    $kmlpath = str_replace("//","/",$kmlpath);
    $xmlstr = file_get_contents($kmlpath);
    $kml = new \SimpleXMLElement($xmlstr);
    if($kml->Document)
    {
      $kmldoc = $kml->Document;
    }
    else if ($kml->Folder)
    {
      $kmldoc = $kml->Folder;
    }
    else
      return $geodata;
    $dist = 0;
    $minlat=360;
    $minlong =360;
    $maxlat=-360;
    $maxlong=-306;
    foreach ($kmldoc->Placemark as $placemark)
    {
     $k=0;
     if($placemark->LineString)
     {
        $coordinatesstr = $placemark->LineString->coordinates ;
        $value =  explode(PHP_EOL,  $coordinatesstr[0]);
     }
      else
      {
        $coordinatesstr = $placemark->Polygon->outerBoundaryIs->LinearRing->coordinates;
        $valueb= $coordinatesstr->__toString();
        $value = preg_split("@[\s+　]@u", trim($valueb));
      }

      $coords   = array();
      foreach($value as $coord)
      {
        $args     = explode(",", $coord);
        if(isset($args[1]))
        {
          $coords[] = array($args[0], $args[1]);
          if($k>0)
          {
            $dist += $this->getDistanceBetweenTwoPoints($coords[$k], $coords[$k-1]);
            if($maxlat < $coords[$k][1])$maxlat= $coords[$k][1];
            if($maxlong < $coords[$k][0])$maxlong= $coords[$k][0];
            if($minlat > $coords[$k][1])$minlat= $coords[$k][1];
            if($minlong > $coords[$k][0])$minlong= $coords[$k][0];
          }
          $k++;
        }
      }
    }
    $geodata["dist"]=number_format((float)$dist, 2, '.', '');
    $geodata["maxlat"]=$maxlat;
    $geodata["midlat"]="".($maxlat+$minlat)/2;
    $geodata["minlat"]=$minlat;
    $geodata["maxlong"]=$maxlong;
    $geodata["midlong"]="".($minlong+$maxlong)/2;
    $geodata["minlong"]=$minlong;
    return  $geodata;
  }

}


