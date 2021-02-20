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
        $this->mappath =  $this->params->get('maproot');
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
              $maps = scandir($this->mappath);
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


    public function findmap($mapkey,$year="",$districtid="*")
    {
        $key = $districtid."_".$mapkey."_".$year.".kml";
        if($this->ismap($key) )  return $key;
        $key = $districtid."_".$mapkey.".kml";
        if($this->ismap($key) )  return $key;
        $key = $mapkey."_".$year.".kml";
        if($this->ismap($key) )  return $key;
        $key = $mapkey.".kml";
        if($this->ismap($key) )  return $key;
        else
          return null;
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
}
