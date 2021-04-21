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

//use App\Service\PDF;
//use Fpdf\Fpdf;
//use Dompdf\Exception;

//require('fpdf.php');

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
        if(!$seat->getKML())
        {
             $seat->setKML($this->mapserver->findseat($seat->getSeatId(),$this->rgyear,$district->getDistrictId()));

         }
        $roadgrouplinks = $this->getDoctrine()->getRepository("App:Seat")->findRoadgroups($dtid,$stid,$this->rgyear);

        $rglist = array();
        $bounds =$this->mapserver->newbounds();
        foreach ($roadgrouplinks as $aroadgrouplink)
        {
           $aroadgroup =  $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($aroadgrouplink["roadgroupid"],$this->rgyear);
           $bounds = $this->mapserver->expandbounds($bounds,$aroadgroup->getBounds());
           dump($bounds);
           $kml = $aroadgroup->getKML();
           if($aroadgroup)
           {
            if(!$this->mapserver->ismap($kml))
           {
             //$aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupId(),$this->rgyear));
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
                'bounds'=>$bounds,
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
        $rgs = [];
        foreach($pds as $key =>  $pd)
        {
           $thh = 0;
          $pdid = $pd["pollingdistrictid"];
         $rglist = $this->getDoctrine()->getRepository("App:Roadgroup")->findAllinPollingDistrict($pdid, $this->rgyear);
         $roadgroups = [];
         foreach ($rglist as $rg)
         {
            $aroadgroup = $this->getDoctrine()->getRepository("App:Roadgroup")->findOne($rg["roadgroupid"],$this->rgyear);
            $kml = $aroadgroup->getKML();
            if(!$this->mapserver->ismap($kml))
            {
               $aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupId(),$this->rgyear));
            }
            $roadgroups[] = $aroadgroup;
            $sum=  $this->getDoctrine()->getRepository("App:Roadgroup")->countHouseholds($rg["roadgroupid"],$this->rgyear);
            $thh+= $sum;
          }
          $rgs[$pdid]= $roadgroups;
          $pd["households"] = $thh;
          $pds[$key]=$pd;
        }

        $sparepds = $this->getDoctrine()->getRepository("App:Pollingdistrict")->findSpares($district->getDistrictId(),$this->rgyear );

        return $this->render('seat/showpds.html.twig',
            [
                'rgyear' => $this->rgyear,
                'message' =>  '' ,
                'district'=> $district,
                'seat' => $seat,
                'pds'=>$pds,
                'rgs'=>$rgs,
                 'sparepds' => $sparepds,
                 'back'=>"/district/show/".$dtid,
            ]);
    }



    public function edit($dtid, $stid)
    {
        $request = $this->requestStack->getCurrentRequest();
        if($dtid )
        {
            $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);;
        }
        if($stid)
        {
            $seat = $this->getDoctrine()->getRepository('App:Seat')->findOne($dtid,$stid);
        }
        if(! isset($seat))
        {
            $seat = new seat();
            $seat->setDistrictId($dtid);
        }
       $form = $this->createForm(SeatForm::class, $seat);
      //  $formFactory = Forms::createFormFactory();
      //  $form = $formFactory->createBuilder(SeatForm::class, $seat, ['csrf_protection' => false])->getForm();
        if ($request->getMethod() == 'POST')
        {
           $form->handleRequest($request);
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($seat);
                $entityManager->flush();
                $stid = $seat->getDistrictId();
                return $this->redirect('/district/show/'.$dtid);
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
    $this->getDoctrine()->getRepository('App:Seat')->addpd($dtid,$stid,$pdid,$this->rgyear);
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


/*      public function topdf($list)
    {
     $image = "./maps/BLY_C2.png";
     $file = "./maps/lichfielddc.pdf";
// Instanciation of inherited class
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(5, 5 , 5, 5);
$pdf->SetFont('Helvetica','',12);
$cellw =($this->A4w-(5+5+5))/2-4;
$cellh =($this->A4h-(5+5+5))/2-8;
$p=0;
while($p < count($list))
{
for($i=0;$i<=1;$i++)
{
  for($j=0;$j<=1;$j++)
  {
   $x = 5+$j*(5+$cellw);
   $y = 5+$i*(5+$cellh);
    $image = "./maps/".$list[p].".png";
   $pdf->setXY($x,$y);
    $pdf->Image($image,$x,$y,$cellw,$cellh);
   $pdf->Cell($cellw,$cellh,'',1,1,'C');
   $p++;
   $ip++;
  }
}
 if($p<count($list))
   {
   $pdf->AddPage();
   }
}
//$pdf->Output();



             $pdf->Output($file,'F');
            // file_put_contents($file, $output);
          //  $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
           // fwrite($handle, $xmlout) or die ("ERROR: Cannot write the file.");
          //  fclose($handle);
            $this->addFlash('notice','PDF file saved..' );
            return $this->redirect("/");
    }
*/
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
