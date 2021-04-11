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
        $rdroot = "roadgroups/";
        $key1 = $this->maproot.$rdroot.$seatid."_".$mapkey."_".$year.".kml";
        $key = $seatid."_".$mapkey."_".$year.".kml";
        if(!file_exists($key1))
        {
          $key1 = $this->maproot.$rdroot.$mapkey."_".$year.".kml";
          $key = $mapkey."_".$year.".kml";
        }
        if(!file_exists($key1))
        {
           $key1 = $this->maproot.$rdroot.$mapkey."_".$year.".kml";
           $key =  $mapkey."_".$year.".kml";
        }
        if(!file_exists($key1))
        {
           $key1 = $this->maproot.$rdroot.$mapkey.".kml";
           $key =  $mapkey.".kml";
        }
        if(file_exists($key1))  return trim($key);
        else
        {
          echo '<script>console.log(" Not Found rg : '.$key1.'")</script>';
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


    public function maxnw($nwest1, $nwest2)
    {
      if($nwest1 === null)  $nwest1 = $nwest2;
      if($nwest1[0] < $nwest2[0]) $nwest1[0] = $nwest2[0];
      if($nwest1[1] < $nwest2[1]) $nwest1[1] = $nwest2[1];

     return $nwest1;
    }


     public function minse($seast1, $seast2)
     {
        if($seast1 === null)  $seast1= $seast2;
        if($seast1[0] >  $seast2[0]) $seast[0] = $seast2[0];
        if($seast1[1] >  $seast2[1]) $seast[1] = $seast2[1];

        return $seast1;
     }
}
