<?php

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
;

use Symfony\Component\HttpFoundation\Response;


use App\Form\Type\WardForm;
use App\Form\Type\NewSubwardForm;
use App\Form\Type\SubwardForm;
use App\Entity\Ward;
use App\Entity\Subward;
use App\Entity\Roadgroup;




class WardController extends AbstractController
{



    private $requestStack ;

    public function __construct( RequestStack $request_stack)
    {

        $this->requestStack = $request_stack;

    }


    public function showall()
    {

        $wards = $this->getDoctrine()->getRepository("App:Ward")->findAll();
        if (!$wards) {
            return $this->render('ward/showall.html.twig', [ 'message' =>  'wards not Found',]);
        }
        $wardlist = array();
        foreach (  $wards as $award )
        {
           $wardlist[] = $award->getWardId();
        }
        $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findNotInWardList($wardlist);
         $extraroadgroups =  $this->getDoctrine()->getRepository("App:Roadgroup")->findNotInWardList($wardlist);
        return $this->render('ward/showall.html.twig',
                              [
                                'message' =>  '' ,
                                'heading' => 'The wards',
                                'wards'=> $wards,
                                'streets'=>$extrastreets,
                                'roadgroups'=>$extraroadgroups,
                                ]);
    }


    public function showone($wdid)
    {
        $ward = $this->getDoctrine()->getRepository("App:Ward")->findOne($wdid);
        if (!$ward) {
            return $this->render('ward/showone.html.twig', [ 'message' =>  'ward not Found',]);
        }
        $subwards = $this->getDoctrine()->getRepository("App:Subward")->findChildren($wdid);

        $sublist = array();
        $swlist = array();
        foreach ($subwards as $asubward)
        {
           $swid =  $asubward->getSubwardId();
           $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swid);
           $rglist= array();
           foreach ($roadgroups as $aroadgroup)
           {
           $rglist[]=$aroadgroup->getRoadgroupid();
           }
           $swlist[$swid]=$rglist;
           $sublist[] = $swid;

        }
      //      dump($swlist);
     //   $findChar =strrpos($sublist,"'");
     //   $sublist[$findChar]=")";
      $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findNotInSubList($wdid,$sublist);
        return $this->render('ward/showone.html.twig',
            [
                'message' =>  '' ,
                'ward'=> $ward,
                'subwards'=> $subwards,
                'streets'=>$extrastreets,
                 'roadgrouplist'=>$swlist,
                'back'=>"/ward/showall"
            ]);
    }

     public function showsubward($swdid)
    {
        $subward = $this->getDoctrine()->getRepository("App:Subward")->findOne($swdid);
        if (!$subward) {
            return $this->render('subward/showone.html.twig', [ 'message' =>  'subward not Found',]);
        }

        $wardid =  $subward->getWardId();
        $ward = $this->getDoctrine()->getRepository("App:Ward")->findOne($wardid);
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swdid);
        return $this->render('subward/showone.html.twig',
                              [
                                'message' =>  '' ,
                                'ward'=>$ward,
                                'subward'=>$subward,
                                'roadgroups'=> $roadgroups,
                                 'back'=>"/ward/show/".$wardid,
                                ]);
    }


     public function wardEdit($pid)
    {
        $request = $this->requestStack->getCurrentRequest();
        //$user = $this->getUser();
       // $time = new \DateTime();
        if($pid>0)
        {
            $ward = $this->getDoctrine()->getRepository('App:ward')->findOne($pid);
        }
        if(! isset($ward))
        {
            $ward = new ward();
        }
        $form = $this->createForm(wardForm::class, $ward);
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
               // $person->setContributor($user->getUsername());
               // $person->setUpdateDt($time);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ward);
                $entityManager->flush();
                $pid = $ward->getwardId();
                return $this->redirect("/ward/edit/".$pid);
            }
        }

        return $this->render('ward/edit.html.twig', array(
            'form' => $form->createView(),
            'objid'=>$pid,
            'ward'=>$ward,
            'returnlink'=>'/ward/problems',
            ));
    }

   public function newward()
    {
        $request = $this->requestStack->getCurrentRequest();

            $ward = new ward();

        $form = $this->createForm(WardForm::class, $ward);
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
               // $person->setContributor($user->getUsername());
               // $person->setUpdateDt($time);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ward);
                $entityManager->flush();
                $pid = $ward->getwardId();
                return $this->redirect("/ward/edit/".$pid);
            }
        }

        return $this->render('ward/edit.html.twig', array(
            'form' => $form->createView(),
            'ward'=>$ward,
            'returnlink'=>'/ward/showall',
            ));
    }

     public function NewSubward($wdid)
    {
        $request = $this->requestStack->getCurrentRequest();
            $subward = new Subward();
            $subward->setWardid($wdid);
            $form = $this->createForm(NewSubwardForm::class, $subward);
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
               // $person->setContributor($user->getUsername());
               // $person->setUpdateDt($time);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($subward);
                $entityManager->flush();
                $pid = $subward->getSubwardId();
                return $this->redirect("/ward/show/".$wdid);
            }
        }

        return $this->render('subward/new.html.twig', array(
            'form' => $form->createView(),
            'wardid'=>$wdid,
            'subward'=>$subward,
            'returnlink'=>'/ward/show/'.$wdid,
            ));
    }

    public function EditSubward($swdid)
    {
        $request = $this->requestStack->getCurrentRequest();
         if($swdid != "")
        {
            $subward = $this->getDoctrine()->getRepository('App:Subward')->findOne($swdid);
        }
        if(! isset($subward))
        {
            $subward = new subward();
        }
            $form = $this->createForm(SubwardForm::class, $subward);
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
               // $person->setContributor($user->getUsername());
               // $person->setUpdateDt($time);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($subward);
                $entityManager->flush();
                $pid = $subward->getSubwardId();
                return $this->redirect("/ward/show/".$swdid);
            }
        }

        return $this->render('subward/edit.html.twig', array(
            'form' => $form->createView(),
            'subward'=>$subward,
            'returnlink'=>'/street/problems',
            ));
    }


     public function DeleteSubward($swdid)
    {

            $subward = $this->getDoctrine()->getRepository('App:Subward')->findOne($swdid);
            $wdid = $subward->getWardId();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($subward);
                $entityManager->flush();

                return $this->redirect("/ward/show/".$wdid);

    }



}
