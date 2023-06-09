<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Cookie;


use Symfony\Component\HttpFoundation\Response;

use App\Service\MapServer;
use App\Form\Type\StreetForm;
use App\Entity\Street;



;
//use Dompdf\Dompdf as Dompdf;
//use Dompdf\Options;


class MainController extends AbstractController
{

    private $requestStack ;
    private $rgyear ;
    private $mapserver;

    public function __construct( RequestStack $request_stack,  MapServer $mapserver)
    {
        $this->requestStack = $request_stack;
        $this->mapserver = $mapserver;
        $this->mapserver->load(true);
        $this->rgyear  = $this->requestStack->getCurrentRequest()->cookies->get('rgyear');
    }


    public function mainmenu()
    {

         $years = $this->getDoctrine()->getRepository("App:Roadgrouptostreet")->getYears();
         if(!$this->rgyear)
         {
            $this->rgyear  = date('Y');
            $cookie = new Cookie('rgyear',	$this->rgyear,	time() + ( 24 * 60 * 60));
		        $res = new Response();
            $res->headers->setCookie( $cookie );
            $res->send();
         }

        $message = "";
        $districts = $this->getDoctrine()->getRepository("App:District")->findAllIndexed();
        $streets = $this->getDoctrine()->getRepository("App:Street")->findAll();
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findAllCurrent($this->rgyear);
        if (!$streets)
        {
            $message .= 'Streets not Found\n';
        }else
        {
            $message .= count( $streets). " Streets found\n ";
        }
           dump($districts);
          return $this->render('rgmenu.html.twig',
          [
            'rgyear'=>$this->rgyear,
            'message' =>  '' ,
            'districts'=> $districts,
            'years'=>$years,
          ]);
    }


    public function selectyear($year)
    {
        $cookie = new Cookie('rgyear',	$year,	time() + ( 24 * 60 * 60));
		    $res = new Response();
        $res->headers->setCookie( $cookie );
        $res->send();
        return $this->redirect("/rggroup/showall");
    }

}
