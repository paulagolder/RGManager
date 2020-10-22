<?php
// src/Controller/PersonController.php
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

use App\Service\FileUploader;
use App\Form\Type\RoadgroupForm;
use App\Form\Type\StreetForm;
use App\Entity\Roadgroup;
use App\Entity\Street;



;
//use Dompdf\Dompdf as Dompdf;
//use Dompdf\Options;


class RoadgroupController extends AbstractController
{



  private $requestStack ;

  public function __construct( RequestStack $request_stack)
  {

    $this->requestStack = $request_stack;

  }


  public function Showall()
  {
    $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findAll();
    if (!$roadgroups) {
      return $this->render('roadgroup/showall.html.twig', [ 'message' =>  'roadgroups not Found',]);
    }

    return $this->render('roadgroup/showall.html.twig',
    [
    'message' =>  '' ,
    'heading' => 'The Road Groups',
    'roadgroups'=> $roadgroups,
    'back'=>"/",
    ]);

  }

  public function Showone($rgid)
  {
    $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid);
    $swdid = $roadgroup->getSubwardId();
    $wdid = $roadgroup->getWardId();
    $stlist = [];
    $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($rgid);
    foreach($streets as $astreet)
    {
        $stlist[]= $astreet->getStreetId();
    }
    if($swdid)
       $back = "/subward/show/".$swdid;
    else if($wdid)
       $back =  "/ward/show/".$wdid;
    else
       $back =  "/ward/showall/";
    $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets();


    return $this->render('roadgroup/showone.html.twig',
    [
    'message' =>  '' ,
    'roadgroup' => $roadgroup ,
    'streets'=> $streets,
    'sparestreets'=>$extrastreets,
    'back'=>$back,
    ]);

  }

  public function Edit($rgid)
  {
    $request = $this->requestStack->getCurrentRequest();
    if($rgid)
    {
      $roadgroup = $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid);
       $swdid = $roadgroup->getSubwardId();
       $wdid = $roadgroup->getWardId();
    }
    if(! isset($roadgroup ))
    {
      $roadgroup  = new Roadgroup ();
    }
    $form = $this->createForm(RoadgroupForm::class, $roadgroup );
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
       $swdid = $roadgroup->getSubwardId();
       $wdid = $roadgroup->getWardId();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($roadgroup );
        $entityManager->flush();
        $rgid = $roadgroup->getRoadgroupId();
        if($swdid)
           return $this->redirect("/subward/show/".$swdid);
        else if($wdid)
           return $this->redirect("/ward/show/".$wdid);
         else
            return   $this->redirect("/ward/showall/");
      }
    }

    if($swdid)
           $back = "/subward/show/".$swdid;
        else if($wdid)
            $back =  "/ward/show/".$wdid;
         else
             $back =  "/ward/showall/";
    return $this->render('roadgroup/edit.html.twig', array(
      'form' => $form->createView(),
      'roadgroup'=>$roadgroup,
      'back'=>$back,
      ));
  }

  public function New($wdid, $swdid)
  {
    $request = $this->requestStack->getCurrentRequest();

    $roadgroup  = new Roadgroup ();
    $roadgroup-> setSubwardId($swdid);
    $roadgroup-> setWardId($wdid);
    // $roadgroup-> setStreetcount (0);
    $form = $this->createForm(RoadgroupForm::class, $roadgroup );
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($roadgroup );
        $entityManager->flush();
        $rdid = $roadgroup->getRoadgroupId();
        return $this->redirect("/subward/show/".$swdid);
      }
    }

    return $this->render('roadgroup/edit.html.twig', array(
      'form' => $form->createView(),
      'roadgroup'=>$roadgroup,
      'back'=>'/subward/show/'.$swdid,
      ));
  }

  public function StreetEdit($pid)
  {
    $request = $this->requestStack->getCurrentRequest();
    //$user = $this->getUser();
    // $time = new \DateTime();
    if($pid>0)
    {
      $street = $this->getDoctrine()->getRepository('App:Street')->findOne($pid);
    }
    if(! isset($street))
    {
      $street = new Street();
    }
    $form = $this->createForm(StreetForm::class, $street);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        // $person->setContributor($user->getUsername());
        // $person->setUpdateDt($time);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($street);
        $entityManager->flush();
        $pid = $street->getStreetId();
        return $this->redirect("/street/edit/".$pid);
      }
    }

    return $this->render('street/edit.html.twig', array(
      'form' => $form->createView(),
      'street'=>$street,
      'returnlink'=>'/street/problems',
      ));
  }

  public function newstreet($rgid)
  {
    $request = $this->requestStack->getCurrentRequest();
    $roadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rgid);
    $astreet = new Street();
    $astreet->setRoadgroupId($rgid);
    $astreet->setSubwardId($roadgroup->getSubwardId());
    $astreet->setWardId($roadgroup->getWardId());
    $astreet->setRoadgroupId($rgid);
    $form = $this->createForm(StreetForm::class, $astreet);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        // $person->setContributor($user->getUsername());
        // $person->setUpdateDt($time);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($astreet);
        $entityManager->flush();
        $pid = $astreet->getStreetId();
        return $this->redirect("/roadgroup/show/".$rgid);
      }
    }

    return $this->render('street/edit.html.twig', array(
      'form' => $form->createView(),
      'street'=>$astreet,
      'back'=>'/roadgroup/show/'.$rgid,
      ));
  }

  public function Delete($rgid)
  {

    $roadgroup = $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid);
    $swdid = $roadgroup->getSubwardId();
       $wdid = $roadgroup->getWardId();
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($roadgroup);
    $entityManager->flush();
    if($swdid)
           $back = "/subward/show/".$swdid;
        else if($wdid)
            $back =  "/ward/show/".$wdid;
         else
             $back =  "/ward/showall/";
    return $this->redirect($back);

  }





}
