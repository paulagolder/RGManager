<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use stdClass;
use App\Entity\Geodata;
use DOMDocument;

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
        $maps = scandir($this->maproot."roadgroups");

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
      echo '<script>console.log(" Not Found map  : '.$key0.'")</script>';
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




  public function newGeodata()
  {
     $geodata = new stdClass();
     $geodata->minlat = null;
     $geodata->maxlat = null;
     $geodata->maxlong = null;
     $geodata->minlong = null;
     $geodata->midlong = null;
     $geodata->midlat =null;
     $geodata->dist = 0;
    return  $geodata;
  }



  public function makebounds_fromarray($obounds)
  {
    $bounds = $this->newbounds();
    if($obounds == null) return $bounds;
   // $obounds = json_decode($sbounds);
    $bounds["minlat"] =  $obounds["minlat"];
    $bounds["maxlat"] = $obounds["maxlat"] ;
    $bounds["maxlong"] = $obounds["maxlong"];
    $bounds["minlong"] = $obounds["minlong"];
    return $bounds;
  }





  public function expandboundsobj($rootbounds, $sbounds)
  {
    if (!$sbounds) return $rootbounds;;
    if(! is_object($sbounds))
      if(is_array($sbounds))
      {
        $bounds = (object)$sbounds;
      }
      else
        $bounds = json_decode($sbounds);
    else
      $bounds= $sbounds;

    if($rootbounds->minlat === null)  $rootbounds->minlat =  $bounds->minlat;
    if($rootbounds->maxlong === null)  $rootbounds->maxlong =  $bounds->maxlong;
    if(($bounds->minlat !== null) && ($rootbounds->minlat >  $bounds->minlat)) $rootbounds->minlat = $bounds->minlat;
    if(($bounds->maxlong !== null) && ($rootbounds->maxlong <  $bounds->maxlong)) $rootbounds->maxlong = $bounds->maxlong;
    if($rootbounds->maxlat === null)  $rootbounds->maxlat =  $bounds->maxlat;
    if($rootbounds->minlong === null)  $rootbounds->minlong =  $bounds->minlong;
    if(($bounds->maxlat !== null) && ( $rootbounds->maxlat < $bounds->maxlat))  $rootbounds->maxlat= $bounds->maxlat;
    if(($bounds->minlong !== null) && ( $rootbounds->minlong > $bounds->minlong))  $rootbounds->minlong = $bounds->minlong;
    return $rootbounds;
  }

  public function mergeGeodata($rootgeodata, $ingeodata)
  {
    if (!$ingeodata) return $rootgeodata;;
    if(! is_object($ingeodata))
      if(is_array($ingeodata))
      {
        $ageodata = (object)$ingeodata;
      }
      else
        $ageodata = json_decode($ingeodata);
      else
        $ageodata= $ingeodata;

      if($rootgeodata->minlat === null)  $rootgeodata->minlat =  $ageodata->minlat;
      if($rootgeodata->maxlong === null)  $rootgeodata->maxlong =  $ageodata->maxlong;
      if(($ageodata->minlat !== null) && ($rootgeodata->minlat >  $ageodata->minlat)) $rootgeodata->minlat = $ageodata->minlat;
      if(($ageodata->maxlong !== null) && ($rootgeodata->maxlong <  $ageodata->maxlong)) $rootgeodata->maxlong = $ageodata->maxlong;
      if($rootgeodata->maxlat === null)  $rootgeodata->maxlat =  $ageodata->maxlat;
      if($rootgeodata->minlong === null)  $rootgeodata->minlong =  $ageodata->minlong;
      if(($ageodata->maxlat !== null) && ( $rootgeodata->maxlat < $ageodata->maxlat))  $rootgeodata->maxlat= $ageodata->maxlat;
      if(($ageodata->minlong !== null) && ( $rootgeodata->minlong > $ageodata->minlong))  $rootgeodata->minlong = $ageodata->minlong;

      $rootgeodata->dist =  $rootgeodata->dist +$rootgeodata->dist;



      return $rootgeodata;
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

  public function newkml($name)
  {

    $newkml = "";
    $totaldist =0;
    foreach($streets as $astreet)
    {
      $path = $astreet->getDecodedpath();
      foreach($path as $branch)
      {
        if(count($branch->steps)>1)
        {

          // $geodata = $this->loadBranch($branch->steps);
          // $dist += $geodata["dist"];
          $newkml .=  "<Placemark>\n";
          $newkml .=  "  <name>".$astreet->getName()."</name>\n";
          $newkml .=  "  <styleUrl>#blueLine</styleUrl>\n";
          $newkml .=  "  <LineString>\n";
          $newkml .=  "	   <coordinates>\n";
          foreach($branch->steps as $step)
          {
            $newkml .="".$step[1].",".$step[0]."\n";
          }
          $newkml .=  "    </coordinates>\n";
          $newkml .=  "  </LineString>\n";
          $newkml .=  "</Placemark>\n";
        }
      }
      $totaldist += $astreet->getDistance();
    }
    return $newkml;
  }

  function makekmldoc($kmlname)
  {
    $xmlout = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xmlout .= "<kml xmlns=\"http://www.opengis.net/kml/2.2\">\n";
    $xmlout .=  "<Document>\n";
    $xmlout .=  " <name>".$kmlname."</name>\n";
    $xmlout .=  "<Style id=\"blueLine\">\n";
    $xmlout .=  "  <LineStyle>\n";
    $xmlout .=  "    <color>7fff0000</color>\n";
    $xmlout .=  "    <width>4</width>\n";
    $xmlout .=  "  </LineStyle>\n";
    $xmlout .=  "</Style>\n";
    $xmlout .=  "</Document>\n";
    $xmlout .=  "</kml>\n";
    $doc = new DOMDocument();
    $doc->loadXML($xmlout);
    return $doc;
  }



  public function mergeRoute($kmldom,$addfile)
  {
      $kmldoc = $kmldom;
    $addpath =  $this->maproot."roadgroups/".$addfile;
    $addpath = str_replace("//","/",$addpath);
    $xmlstr2 = file_get_contents($addpath);
    $addkml = new \SimpleXMLElement($xmlstr2);

    foreach ($addkml->Placemark as $placemark)
    {
      $kmldoc->appendChild($placemark);
    }
   return $kmldoc;
  }

  public function scanRoute($addfile)
  {
       $geodata = new Geodata;
    $minlat = 360;
    $maxlat = -360;
    $minlng = 360;
    $maxlng = -360;
    if($addfile)
    {
        $addpath =  $this->maproot.$addfile;
    $addpath = str_replace("//","/",$addpath);;
    $xmlstr2 = file_get_contents($addpath);
    $addkml = new \SimpleXMLElement($xmlstr2);
    $placemarks = $addkml->Document->Placemark;
    foreach ($placemarks as $placemark)
    {
      $coords = $placemark->Polygon->outerBoundaryIs->LinearRing->coordinates;
      if(strstr($coords[0], "\n"))
      {
      $carray = preg_split("/\r\n|\n|\r/", $coords[0]);
      }else
     {
       $carray = explode(" ",$coords[0]);
    }

      foreach( $carray as $coord)
      {

        $latlng = explode(",",$coord);
        if(count($latlng )>1)
        {
          $lat =  floatval($latlng[1]);
          $lng =  floatval($latlng[0]);
          if($maxlat < $lat)$maxlat= $lat;
          if($maxlng < $lng)$maxlng= $lng;
          if($minlat > $lat)$minlat= $lat;
          if($minlng > $lng)$minlng= $lng;
        }

      }
    }

    $geodata->maxlat = $maxlat;
    $geodata->maxlong = $maxlng;
    $geodata->minlat = $minlat;
    $geodata->minlong = $minlng;
    $geodata->midlat = ($minlat+$maxlat)/2;
    $geodata->midlong = ($minlng+$maxlng)/2;
    }
      return $geodata;
  }

  function getColor($i)
  {

   $colors= [];
   $colors[0]="#FF000088";
   $colors[1]="#00FF0088";
   $colors[2]="#0000FF88";
   $colors[3]="#FFFF0088";
   $colors[4]="#FF00FF88";
   $colors[5]="#00FFFF88";
   $colors[6]="#80000088";
   $colors[7]="#00800088";
   $colors[8]="#00008088";
   $colors[9]="#80800088";
   $colors[10]="#80008088";
   $colors[11]="#00808088";
   $colors[12]="#5a5a5a88";
   if($i>12) $i = $i-12;
    return $colors[$i];
  }



  function getFillStyle($i)
  {
      $fillStyle =[];
      $fillStyle["color"]=  'grey' ;
      $fillStyle["fillColor"]=  'green' ;
      $fillStyle["weight"]=  4 ;
      $fillStyle["clickable"]= true ;
      $fillStyle["opacity"]=  1 ;
      $fillStyle["fillOpacity"]=  0.5;
      $fillStyle["fill"]=  true;

    $istyle= fillStyle;
    $istyle["color"]  = getColor($i) ;
    $istyle["fillColor"] =  getColor($i) ;
    return istyle;
  }


  function hexColor($color)
  {
    $rgb = dechex(($color[0]<<16)|($color[1]<<8)|$color[2]);
    return("#".substr("000000".$rgb, -6));
  }

  function makeColor($intval)
  {
    $c =[0.206, 0.281, 0.368, 0.457];

    $r = 255;
    $b = 255;
    $g  = 255;

    if($intval<= 0)
    {
      $r = 80;
      $b = 80;
      $g  = 80;
    }elseif($intval<$c[0])
    {
      $r = 0;
      $b = 255;
      $g  = 0;
    }elseif($intval<$c[1])
      {
        $r = 137;
        $b = 240;
        $g  =207;
      }
      elseif($intval <$c[2])
      {
        $r = 0;
        $b = 0;
        $g  = 255;
      }
      elseif($intval <$c[3])
      {
        //255,182,193
        $r = 255;
        $b = 182;
        $g  = 193;
      } elseif($intval > 1)
      {
        $r = 0;
        $b = 0;
        $g  = 0;
      }else
      {
        $r = 225;
        $b = 0;
        $g  = 0;
      }
    return $this->rgbToHex($r,$g,$b);
  }

  function componentToHex($c)
  {
    $hex = dechex($c);
    if( strlen($hex)< 2)
      return "0".$hex;
    else
      return $hex;
  }

  function rgbToHex($r, $g, $b)
  {
    return "#".$this->componentToHex($r).$this->componentToHex($g).$this->componentToHex($b);
  }


  function makebounds_streetlist($streets)
  {
      $geodata =new Geodata;
      foreach( $streets as $street)
      {
        if( array_key_exists("children",$street))
        {
          $dgeodata = $this->makegeodata_roadgroups($street["children"]);
          $geodata = $this->expandgeodata($geodata, $dgeodata);
        }
        else
        {
          if( array_key_exists("roadgrouplist",$street))
          {
            foreach($street["roadgrouplist"] as $roadgroup)
            {
              $dgeodata = $this->makegeodata($roadgroup["geodata"]);
              $geodata = $this->expandgeodata($geodata, $dgeodata);
            }
          }
          elseif(  property_exists($street,"Geodata"))
          {
            $dgeodata = $street->getGeodata();
            $geodata = $this->mergeGeodata($geodata, $dgeodata);
          }
        }
      }
      return $geodata;
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
      $maxlong=-360;
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

    public function make_geodata_steps_obj($tracks)
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
      $maxlong=-360;
      $nsteps =0;
      foreach($tracks as $track)
      {
      $oldcoords = ($track->steps)[0];
      foreach($track->steps as $step)
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


    public function isinside($geo1,$geo2)
    {
      if( ($geo1->midlat < $geo2->maxlat) && ($geo1->midlat > $geo2->minlat))
      {
        if( ($geo1->midlong < $geo2->maxlong) && ($geo1->midlong > $geo2->minlong))
          return true;
      }
      return false;
    }







}


