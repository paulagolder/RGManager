<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AppExtension extends AbstractExtension
{
    private $session;

     public function __construct(SessionInterface $session)
     {
         $this->session = $session;
     }

    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('area', [$this, 'calculateArea']),
            new TwigFunction('findmap', [$this, 'findMap']),
        ];
    }

    public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }


    public function calculateArea(int $width, int $length)
    {
        return $width * $length;
    }



    public function findMap(String $district, String $mapkey,String $year)
    {
        try
        {

                 $session = $this->session;
                 $rmaps = $session->get('maps');
                 if(!$rmaps)
                 {
                    //$mappath= $this->container->get('parameter_bag')->get('mappath');
                    $mappath ="/home/paul/Development/rgmanager/public/maps/";
                   // $mappath ="http://rgmanager.lerot.org/maps/";
                    $rmaps = scandir($mappath);
                    $session->set('maps', $rmaps);

                 }

                    $key = $district."_".$mapkey."_".$year.".kml";
                    $k = array_search($key, $rmaps);
                    if($k <2)
                    {
                        $key = $mapkey."_".$year.".kml";
                        $k = array_search($key, $rmaps);
                    }
                    if($k <2)
                    {
                        $key = $district."_".$mapkey.".kml";
                        $k = array_search($key, $rmaps);
                    }
                    if($k <2)
                    {
                        $key = $district."_".$mapkey.".kml";
                        $k = array_search($key, $rmaps);
                    }

                    if($k <2)
                    {
                        $key = $mapkey.".kml";
                        $k = array_search($key, $rmaps);
                    }

                    if($k>2)
                    {
                        return ($rmaps[$k]);
                    }
                    else
                       return null;

        } catch (FileException $e){

             $this->addFlash('notice','maps not found' );
        }


    }
}
