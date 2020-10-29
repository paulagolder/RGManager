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

use App\Form\Type\SeatForm;
use App\Form\Type\NewSubwardForm;
use App\Form\Type\SubwardForm;
use App\Entity\Seat;
use App\Entity\District;
use App\Entity\Subward;
use App\Entity\Roadgroup;

//use App\Service\PDF;
//use Fpdf\Fpdf;
//use Dompdf\Exception;

//require('fpdf.php');

class SeatController extends AbstractController
{

    private $A4w = 210;
    private $A4h = 297;

    private $requestStack ;

    public function __construct( RequestStack $request_stack)
    {
        $this->requestStack = $request_stack;
    }


    public function showall()
    {
        $wards = $this->getDoctrine()->getRepository("App:Ward")->findAll();
        if (!$wards)
        {
            return $this->render('ward/showall.html.twig', [ 'message' =>  'wards not Found',]);
        }

        $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets();
        $extraroadgroups =  $this->getDoctrine()->getRepository("App:Roadgroup")->findLooseRoadgroups();
        return $this->render('ward/showall.html.twig',
                              [
                                'message' =>  '' ,
                                'heading' => 'The wards',
                                'wards'=> $wards,
                                'streets'=>$extrastreets,
                                'roadgroups'=>$extraroadgroups,
                                ]);
    }


    public function showone($dtid,$stid)
    {
       // dump($stid);
        $district = $this->getDoctrine()->getRepository("App:District")->findOne($dtid);
        $seat= $this->getDoctrine()->getRepository("App:Seat")->findOne($dtid,$stid);
        if (!$seat)
        {
            return $this->render('seat/showone.html.twig', [ 'message' =>  'seat not Found',]);
        }
        $roadgrouplinks = $this->getDoctrine()->getRepository("App:Seat")->findRoadgroups($dtid,$stid);
        $rglist = array();
      //    dump($roadgrouplinks);
        foreach ($roadgrouplinks as $aroadgrouplink)
        {
           $aroadgroup =  $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($aroadgrouplink["roadgroupid"]);
           $swid =  $aroadgroup->getSubwardId();
           $wid = $aroadgroup->getWardId();
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
       //dump($rglist);
        return $this->render('seat/showone.html.twig',
            [
                'message' =>  '' ,
                'district'=> $district,
                'seat' => $seat,
                'roadgrouplist'=>$rglist,
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
            'form' => $form->createView(),
            'district'=>$district,
            'seat'=>$seat,
            'returnlink'=>'/district/show/'.$dtid,
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
         $wards = $this->getDoctrine()->getRepository("App:Ward")->findAll();
        if (!$wards) {
            return "";
        }
        foreach (  $wards as $award )
        {
           $subwards =   $this->getDoctrine()->getRepository("App:Subward")->findChildren($award->getWardId());
           $award->{'subwards'} = $subwards;
           foreach($subwards as $asubward)
           {
              $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($asubward->getSubwardId());
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
