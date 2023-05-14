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

use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Forms;

use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

use Symfony\Component\HttpFoundation\Response;
//use Dompdf\Dompdf;
use App\Service\MapServer;
use App\Form\Type\SeatForm;
use App\Form\Type\NewSubwardForm;
use App\Form\Type\SubwardForm;
use App\Entity\Seat;
use App\Entity\District;
use App\Entity\Rgsubgroup;
use App\Entity\Roadgroup;
use App\Entity\Geodata;

class SeatController extends AbstractController
{

    private $A4w = 210;
    private $A4h = 297;

    private $requestStack;
    private $mapserver;
    private $rgyear;

    public function __construct( RequestStack $request_stack, MapServer $mapserver)
    {
        $this->requestStack = $request_stack;
        $this->mapserver = $mapserver;
        $mapserver->load();
        $this->rgyear  = $this->requestStack->getCurrentRequest()->cookies->get('rgyear');
    }


    public function showall()
    {
        $wards = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
        if (!$wards)
        {
            return $this->render('ward/showall.html.twig', [ 'message' =>  'wards not Found',]);
        }

        $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets();
        $extraroadgroups =  $this->getDoctrine()->getRepository("App:Roadgroup")->findLooseRoadgroups();
        return $this->render('ward/showall.html.twig',
                             [
                             'rgyear'=>$this->rgyear,
                             'message' =>  '' ,
                             'heading' => 'The wards',
                             'wards'=> $wards,
                             'streets'=>$extrastreets,
                             'roadgroups'=>$extraroadgroups,
                             ]);
    }


    public function showone($dtid,$stid)
    {

        $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
        $seat= $this->getDoctrine()->getRepository("App:Seat")->findOne($dtid,$stid);
        if (!$seat)
        {
            return $this->render('seat/showone.html.twig', [ 'message' =>  'seat not Found',]);
        }
        if(!$this->mapserver->ismap($seat->getKML()))
        {
            $seat->setKML($this->mapserver->findseat($seat->getSeatId(),$this->rgyear,$district->getDistrictId()));
        }
        $roadgrouplinks = $this->getDoctrine()->getRepository("App:Seat")->findRoadgroups($dtid,$stid,$this->rgyear);
        $rglist = array();

        foreach ($roadgrouplinks as $aroadgrouplink)
        {
            $aroadgroup =  $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($aroadgrouplink["roadgroupid"],$this->rgyear);
            $kml = $aroadgroup->getKML();
            if($aroadgroup)
            {
                if(!$this->mapserver->ismap($kml))
                {
                    $aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupId(),$this->rgyear));
                }
                $swid =  $aroadgroup->getRgsubgroupid();
                $wid = $aroadgroup->getRggroupid();
                if(!array_key_exists($wid, $rglist))
                {
                    $rglist[$wid] = array();
                }
                $wrglist = $rglist[$wid];
                if(!array_key_exists($swid, $wrglist))
                {
                    $wrglist[$swid] =array();
                }
                $wrglist[$swid][]=$aroadgroup;
                $rglist[$wid] =  $wrglist;
            }
        }
        return $this->render('seat/showone.html.twig',
                             [
                             'rgyear' => $this->rgyear,
                             'message' =>  '' ,
                             'district'=> $district,
                             'seat' => $seat,
                             'roadgrouplist'=>$rglist,
                             'back'=>"/district/show/".$dtid,
                             ]);
    }

    public function showrgs($dtid,$stid)
    {

        $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
        $seat= $this->getDoctrine()->getRepository("App:Seat")->findOne($dtid,$stid);
        if (!$seat)
        {
            return $this->render('seat/showone.html.twig', [ 'message' =>  'seat not Found',]);
        }
        $roadgrouplinks = $this->getDoctrine()->getRepository("App:Seat")->findRoadgroups($dtid,$stid,$this->rgyear);
        dump($roadgrouplinks);
        $rglist = array();
        $geodata = $seat->getGeodata_obj();
        foreach ($roadgrouplinks as $aroadgrouplink)
        {
            $aroadgroup =  $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($aroadgrouplink["roadgroupid"],$this->rgyear);
            dump($aroadgroup);
            $geodata->mergeGeodata_obj($aroadgroup->getGeodata_obj());
            $kml = $aroadgroup->getKML();
            if($aroadgroup)
            {
                $swid =  $aroadgroup->getRgsubgroupid();
                $wid = $aroadgroup->getRggroupid();
                if(!array_key_exists($wid, $rglist))
                {
                    $rglist[$wid] = array();
                }
                $wrglist = $rglist[$wid];
                if(!array_key_exists($swid, $wrglist))
                {
                    $wrglist[$swid] =array();
                }
                $wrglist[$swid][]=$aroadgroup;
                $rglist[$wid] =  $wrglist;
            }
        }
        $seat->setGeodata($geodata);
        dump($rglist);
        return $this->render('seat/showone.html.twig',
                             [
                             'rgyear' => $this->rgyear,
                             'message' =>  '' ,
                             'district'=> $district,
                             'seat' => $seat,
                             'roadgrouplist'=>$rglist,
                             'back'=>"/district/show/".$dtid,
                             ]);
    }

    public function showpds($dtid,$stid)
    {

        $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
        $seat= $this->getDoctrine()->getRepository("App:Seat")->findOne($dtid,$stid);
        if (!$seat)
        {
            return $this->render('seat/showone.html.twig', [ 'message' =>  'seat not Found',]);
        }
        if(!$seat->getKML())
        {
            $seat->setKML($this->mapserver->findseat($seat->getSeatId(),$this->rgyear,$district->getDistrictId()));
        }
        $pds = $this->getDoctrine()->getRepository("App:Seat")->findPollingdistricts($dtid,$stid,$this->rgyear);
        dump($pds);
        $sts = [];
        $geodata = $seat->getGeodata_obj();;
        foreach($pds as $key =>  $pd)
        {
            $thh = 0;
            $pdid = $pd["pdid"];
            $stlist = $this->getDoctrine()->getRepository("App:Street")->findAllbyPD($pdid);
            $sts[$pdid]=array();
            foreach ($stlist as $st)
            {
                $astreet= $this->getDoctrine()->getRepository("App:Street")->findOnebySeq($st->getSeq());
                $geodata->mergeGeodata_obj($astreet->getGeodata_obj());
                $sts[$pdid][$st->getSeq()]= $astreet;
                $thh+= $astreet->getHouseholds();
            }
            $pd["households"] = $thh;
            $pds[$key]=$pd;
        }
        dump($sts);
        $seat->setGeodata($geodata);
        $sparepds = $this->getDoctrine()->getRepository("App:Pollingdistrict")->findSpares($district->getDistrictId(),$this->rgyear );

        return $this->render('seat/showpds.html.twig',
                             [
                             'rgyear' => $this->rgyear,
                             'message' =>  '' ,
                             'district'=> $district,
                             'seat' => $seat,
                             'pds'=>$pds,
                             'sts'=>$sts,
                             'sparepds' => $sparepds,
                             'back'=>"/district/show/".$dtid,
                             ]);
    }


    public function showwards($dtid,$stid)
    {

        $district = $this->getDoctrine()->getRepository("App:Seat")->findOne($dtid,$stid);
        dump($district);
          $d1 =$district->getGeodata_obj();
       // $wards= $this->getDoctrine()->getRepository("App:Seat")->findSeats("district");

           $wards= $this->getDoctrine()->getRepository("App:Seat")->findSeatsbyPD($stid);
       dump($wards);
       foreach ($wards as $wardid)
        {
             $ward =  $this->getDoctrine()->getRepository("App:Seat")->findOne(null,$wardid["seatid"]);
             $selwards[] = $ward;
        }

        //dump($selwards);

        if(!$district->getKML())
        {
           // $seat->setKML($this->mapserver->findseat($seat->getSeatId(),$this->rgyear,$district->getDistrictId()));
        }
        $geodata = $district->getGeodata_obj();;

        return $this->render('seat/showwards.html.twig',
                             [
                             'rgyear' => $this->rgyear,
                             'message' =>  '' ,
                             'district'=> $district,
                             'wards' => $selwards,
                             'back'=>"/district/show/".$dtid,
                             ]);
    }



    public function edit($dtid, $stid)
    {
        $request = $this->requestStack->getCurrentRequest();
        if($dtid )
        {
            $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
            $level = $district->getLevel();
        }
        if($stid)
        {
            $seat = $this->getDoctrine()->getRepository('App:Seat')->findOne($dtid,$stid);
        }
        if(! isset($seat))
        {
            $seat = new seat();
            $seat->setDistrictId($dtid);
            $seat->setLevel($level);
        }
        $form = $this->createForm(SeatForm::class, $seat);
        //  $formFactory = Forms::createFormFactory();
        //  $form = $formFactory->createBuilder(SeatForm::class, $seat, ['csrf_protection' => false])->getForm();
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
               if($seat->getKML())
               {
                $kmlfile = "/outlines/".$seat->getKML();
                dump($kmlfile);
                $geodata =  $this->mapserver->scanRoute($kmlfile);
                dump($geodata);
                $seat->setGeodata($geodata);
                dump($seat);
               }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($seat);
                $entityManager->flush();
                $dtid = $seat->getDistrictId();
                return $this->redirect('/seat/edit/'.$dtid."/".$stid);
            }
        }

        return $this->render('seat/edit.html.twig', array(
            'rgyear'=>$this->rgyear,
            'form' => $form->createView(),
                                                          'district'=>$district,
                                                          'seat'=>$seat,
                                                          'returnlink'=>'/district/show/'.$dtid,
        ));
    }

    public function removepd($dtid, $stid,$pdid)
    {
        $this->getDoctrine()->getRepository('App:Seat')->removepd($dtid,$stid,$pdid,$this->rgyear);
        return $this->redirect('/seat/showpds/'.$dtid."/".$stid);
    }




    public function addpd($dtid,$stid)
    {
        $pdid = $_POST["selpd"];
        $par = explode("-",$pdid);
        $this->getDoctrine()->getRepository('App:Seat')->addpd($dtid,$stid,$pdid,$par[1],$this->rgyear);
        return $this->redirect('/seat/showpds/'.$dtid."/".$stid);
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
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ward);
                $entityManager->flush();
                $pid = $ward->getRggroupid();
                return $this->redirect("/rggroup/edit/".$pid);
            }
        }

        return $this->render('ward/edit.html.twig', array(
            'rgyear'=>$this->rgyear,
            'form' => $form->createView(),
                                                          'ward'=>$ward,
                                                          'returnlink'=>'/rggroup/showall',
        ));
    }



    public function Export()
    {
        $file = "maps/lichfielddc.rgml";
        $xmlout = "";
        $xmlout .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xmlout .= "<?xml-stylesheet type='text/xsl' href='./Stylesheets/rgml.xsl' ?>\n";
        $xmlout .= "<electiondistrict Name='Lichfield City' >\n";
        $xmlout .= $this->makexml();
        $xmlout .= "</electiondistrict>\n";
        $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
        fwrite($handle, $xmlout) or die ("ERROR: Cannot write the file.");
        fclose($handle);
        $this->addFlash('notice','xml file saved' );
        return $this->redirect("/");
    }


    public function makexml()
    {
        $xmlout = "";
        $wards = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
        if (!$wards) {
            return "";
        }
        foreach (  $wards as $award )
        {
            $subwards =   $this->getDoctrine()->getRepository("App:Subward")->findChildren($award->getRggroupid());
            $award->{'subwards'} = $subwards;
            foreach($subwards as $asubward)
            {
                $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($asubward->getRgsubgroupid());
                $asubward->{'roadgrouplist'}=$roadgroups;
                foreach($roadgroups as $aroadgroup)
                {
                    $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($aroadgroup->getRoadgroupid());

                    $aroadgroup->{'streets'}= $streets;
                }
            }
        }

        foreach (  $wards as $award )
        {
            $xmlout .= $award->makexml();
            $xmlout .= "\n";
        }
        return $xmlout;;
    }




}
