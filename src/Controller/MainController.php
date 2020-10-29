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
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;
//use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\HttpFoundation\Response;


use App\Form\Type\StreetForm;
use App\Entity\Street;



;
//use Dompdf\Dompdf as Dompdf;
//use Dompdf\Options;


class MainController extends AbstractController
{

    private $requestStack ;

    public function __construct( RequestStack $request_stack)
    {
        $this->requestStack = $request_stack;
    }


    public function mainmenu()
    {
        $message = "";
        $districts = $this->getDoctrine()->getRepository("App:District")->findAll();
        $streets = $this->getDoctrine()->getRepository("App:Street")->findAll();
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findAll();
        if (!$streets)
        {
            $message .= 'Streets not Found\n';
        }else
        {
            $message .= count( $streets). " Streets found\n ";
        }

        return $this->render('mainmenu.html.twig',
          [
            'message' =>  '' ,
            'districts'=> $districts,
          ]);

    }


    }
