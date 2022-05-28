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

use Symfony\Component\HttpFoundation\Response;
//use Dompdf\Dompdf;

use App\Form\Type\DistrictForm;
use App\Entity\District;


use App\Service\MapServer;

//use App\Service\PDF;
//use Fpdf\Fpdf;
//use Dompdf\Exception;

//require('fpdf.php');

class DistrictController extends AbstractController
{

    private $A4w = 210;
    private $A4h = 297;

    private $requestStack;
    private $mapserver;
    private $rgyear ;

    public function __construct( RequestStack $request_stack, MapServer $mapserver)
    {
        $this->requestStack = $request_stack;
        $this->mapserver = $mapserver;
        $mapserver->load();
       $this->rgyear  = $this->requestStack->getCurrentRequest()->cookies->get('rgyear');
    }

    public function showone($dtid)
    {

        $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
        if (!$district)
        {
            return $this->render('district/showone.html.twig', [ 'message' =>  'district not Found',]);
        }
          $kml = $district->getKML();
          if(!$kml)
          {
             $district->setKML($this->mapserver->finddistrict($district->getDistrictId(),$this->rgyear));
          }

        $seats = $this->getDoctrine()->getRepository("App:Seat")->findChildren($dtid);
        foreach($seats as &$seat)
        {
          $kml = $seat->getKML();
          if(!$kml)
          {
             $seat->setKML($this->mapserver->findseat($seat->getSeatId(),$this->rgyear,$district->getDistrictId()));
          }
           $count = $this->getDoctrine()->getRepository("App:Seat")->countHouseholds($dtid, $seat->getSeatId(), $this->rgyear);

           $seat->setHouseholds($count);

        }
dump($district);
        return $this->render('district/showone.html.twig',
            [
                'rgyear' => $this->rgyear,
                'message' =>  '' ,
                'district'=>$district,
                'seats'=>$seats,
                'back'=>"/"
            ]);
    }

     public function Edit($dtid)
    {
        $request = $this->requestStack->getCurrentRequest();
        if($dtid)
        {
              $adistrict = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
              dump($adistrict);
        }
        if(! isset($adistrict))
        {
            $adistrict= new district();
        }
        $form = $this->createForm(districtForm::class, $adistrict);
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                //$geodata =  $this->mapserver->loadRoute("districts/",$adistrict->getKML());
                //$adistrict->setGeodata($geodata);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($adistrict);
                $entityManager->flush();
                $dtid = $adistrict->getDistrictid();
                return $this->redirect("/district/edit/".$dtid);
            }
        }
        return $this->render('district/edit.html.twig', array(
           'rgyear' => $this->rgyear,
            'form' => $form->createView(),
            'dtid'=>$dtid,
            'district'=>$adistrict,
            'returnlink'=>'/',
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
                $pid = $ward->getRggroupid();
                return $this->redirect("/rggroup/edit/".$pid);
            }
        }

        return $this->render('ward/edit.html.twig', array(
           'rgyear' => $this->rgyear,
            'form' => $form->createView(),
            'ward'=>$ward,
            'returnlink'=>'/rggroup/showall',
            ));
    }

    public function showsubward($swdid)
    {
        $subward = $this->getDoctrine()->getRepository("App:Subward")->findOne($swdid);
        if (!$subward) {
            return $this->render('subward/showone.html.twig', [ 'message' =>  'subward not Found',]);
        }
        $wardid =  $subward->getRggroupid();
        $ward = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($wardid);
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swdid);
        $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets();
        return $this->render('subward/showone.html.twig',
                              [
                                'rgyear' => $this->rgyear,
                                'message' =>  '' ,
                                'ward'=>$ward,
                                'subward'=>$subward,
                                'roadgroups'=> $roadgroups,
                                 'streets'=>$extrastreets,
                                 'back'=>"/rggroup/show/".$wardid,
                                ]);
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
                $pid = $subward->getRgsubgroupid();
                return $this->redirect("/rggroup/show/".$wdid);
            }
        }

        return $this->render('subward/new.html.twig', array(
            'rgyear' => $this->rgyear,
            'form' => $form->createView(),
            'wardid'=>$wdid,
            'subward'=>$subward,
            'returnlink'=>'/rggroup/show/'.$wdid,
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
                $pid = $subward->getRgsubgroupid();
                return $this->redirect("/rggroup/show/".$swdid);
            }
        }

        return $this->render('subward/edit.html.twig', array(
           'rgyear' => $this->rgyear,
            'form' => $form->createView(),
            'subward'=>$subward,
            'returnlink'=>'/street/problems',
            ));
    }


     public function DeleteSubward($swdid)
    {

            $subward = $this->getDoctrine()->getRepository('App:Subward')->findOne($swdid);
            $wdid = $subward->getRggroupid();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($subward);
                $entityManager->flush();

                return $this->redirect("/rggroup/show/".$wdid);

    }

    public function Exportxml($dtid)
    {
            $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
             $kml = $district->getKML();
          if(!$kml)
          {
             $district->setKML($this->mapserver->finddistrict($district->getDistrictId(),$this->rgyear));
          }
           // $file = "maps/".$dtid."_".$this->rgyear.".rgml";
              $file = "rgml/roadgroups.rgml";
            $xmlout = "";
            $xmlout .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $xmlout .= "<?xml-stylesheet type='text/xsl' href='./Stylesheets/rgml.xsl' ?>\n";
            $xmlout .= "<electiondistrict Name='Lichfield City Labour Party' DistrictId='".$dtid."' KML='".$district->getKML()."' >\n";

            $xmlout .= $this->makexml();
            $xmlout .= "</electiondistrict>\n";
            $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
            fwrite($handle, $xmlout) or die ("ERROR: Cannot write the file.");
            fclose($handle);
            $this->addFlash('notice','xml file saved'.$file );
            return $this->redirect("/");
    }

      public function Exportcsv($dtid)
    {
            $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
            $file = "documents/".$dtid."_".$this->rgyear.".csv";
            $csvout = "";

            $csvout .= "RD-Groups, RD-Sugroups, Roadgroups, Households \n\n";

            $csvout .= $this->makecsv();

            $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
            fwrite($handle, $csvout) or die ("ERROR: Cannot write the file.");
            fclose($handle);
            $this->addFlash('notice','csv file saved: '.$file );
            return $this->redirect("/");
    }


    public function makexml()
    {
         $xmlout = "";
         $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
        if (!$rggroups) {
            return "";
        }
        foreach (  $rggroups as $arggroup )
        {
           $subwards =   $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($arggroup->getRggroupid());
           $arggroup->{'subwards'} = $subwards;
           foreach($subwards as $asubward)
           {
              $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($asubward->getRgsubgroupid(),$this->rgyear);
              $asubward->{'roadgrouplist'}=$roadgroups;
              foreach($roadgroups as $aroadgroup)
              {
                $streetlist = [];
                $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($aroadgroup->getRoadgroupid(),$this->rgyear);
                foreach($streets as $astreet )
                {
                if(array_key_exists($astreet->getName(), $streetlist))
                {
                  $sstreet = $streetlist[$astreet->getName()];


                 $sstreet->setQualifier($sstreet->getQualifier()." ".$astreet->getQualifier());

                  $streetlist[$astreet->getName()]= $sstreet;
                }else
                  $streetlist[$astreet->getName()] = $astreet;

                }
                $aroadgroup->{'streets'}= $streetlist;
                //dump($aroadgroup);
              }
           }
        }

        foreach (  $rggroups as $arggroup )
        {
           $xmlout .= $arggroup->makexml();
           $xmlout .= "\n";
        }
      return $xmlout;;
      }


        public function makecsv()
    {
         $csvout = "";
         $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
        if (!$rggroups) {
            return "";
        }
        foreach (  $rggroups as $arggroup )
        {

           $subwards =   $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($arggroup->getRggroupid());
           $arggroup->{'subwards'} = $subwards;
           foreach($subwards as $asubward)
           {
              $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($asubward->getRgsubgroupid(),$this->rgyear);
              $asubward->{'roadgrouplist'}=$roadgroups;
              foreach($roadgroups as $aroadgroup)
              {
                $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($aroadgroup->getRoadgroupid(),$this->rgyear);

                $aroadgroup->{'streets'}= $streets;
                //dump($aroadgroup);
              }
           }
        }

        foreach (  $rggroups as $arggroup )
        {
           $csvout .= $arggroup->makecsv();
        }
      return $csvout;
      }

    public function showrgs($dtid)
    {

        $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
        $seat= $this->getDoctrine()->getRepository("App:Seat")->findOne($dtid,$stid);
        if (!$seat)
        {
            return $this->render('seat/showone.html.twig', [ 'message' =>  'seat not Found',]);
        }
        $roadgrouplinks = $this->getDoctrine()->getRepository("App:Seat")->findRoadgroups($dtid,$stid,$this->rgyear);

        $rglist = array();
         $bounds =$this->mapserver->newGeodata();
        foreach ($roadgrouplinks as $aroadgrouplink)
        {
           $aroadgroup =  $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($aroadgrouplink["roadgroupid"],$this->rgyear);
           $bounds = $this->mapserver->expandbounds($bounds, $aroadgroup->getGeodata());
           dump($bounds);
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
        dump($rglist);
        return $this->render('seat/showone.html.twig',
            [
                'rgyear' => $this->rgyear,
                'message' =>  '' ,
                'district'=> $district,
                'seat' => $seat,
                'bounds'=>$bounds,
                'roadgrouplist'=>$rglist,
                'back'=>"/district/show/".$dtid,
            ]);
    }

   public function heatmap($dtid)
    {
            $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
    $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
    if (!$rggroups)
    {
      return $this->render('rggroup/showall.html.twig', [ 'message' =>  'RGgroups not Found',]);
    }
    $rglist= array();
    foreach($rggroups as $arggroup)
    {
      $subgroups = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($arggroup->getRggroupid());
      foreach ($subgroups as $asubgroup)
      {
        $swid =  $asubgroup->getRgsubgroupid();
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swid,$this->rgyear);
        foreach ($roadgroups as $aroadgroup)
        {
          if($aroadgroup->getElectors()>0)
             $labness = $aroadgroup->getPLVN()/$aroadgroup->getElectors();
          else
             $labness=0;
          $rg = array();
          $rg["kml"] = $aroadgroup->getKML();
          $rg["priority"] = $labness;
          $rg["color"] = $this->mapserver->makeColor($labness);
          $rg['rgid'] = $aroadgroup->getRoadgroupid();
          $rglist[]=$rg;
        }
      }
    }
    dump($rglist);
    dump($district);
    return $this->render('district/heatmap.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'district'=>$district,
    'rglist'=>$rglist,
    'back'=>"/",
    ]);
  }
}
