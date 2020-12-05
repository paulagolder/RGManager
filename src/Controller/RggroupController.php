<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;


use Symfony\Component\HttpFoundation\Response;
//use Dompdf\Dompdf;

use App\Form\Type\RggroupForm;
use App\Form\Type\NewRgsubgroupForm;
use App\Form\Type\RgsubgroupForm;
use App\Entity\Rggroup;
use App\Entity\Rgsubgroup;
use App\Entity\Roadgroup;
use App\Service\MapServer;

//use App\Service\PDF;
//use Fpdf\Fpdf;
//use Dompdf\Exception;

//require('fpdf.php');

class RggroupController extends AbstractController
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
        $mappath= $this->container->get('parameter_bag')->get('mappath');
        $maproot = $this->container->get('parameter_bag')->get('maproot');
         $topmap = $this->container->get('parameter_bag')->get('topmap');
        $wards = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
        if (!$wards)
        {
            return $this->render('ward/showall.html.twig', [ 'message' =>  'wards not Found',]);
        }
       // dump($wards);
        for($i=0; $i<count($wards); $i++)
        {
          $ward = $wards[$i];
          $rgs = $this->getDoctrine()->getRepository("App:Roadgroup")->findRoadgroupsinRGGroup($ward["rggroupid"]);
          $nrg = 0;
          for($j=0;$j<count($rgs);$j++)
          {
            $rgid = $rgs[$j]->getRoadgroupId();
           $mpath = $maproot.$rgid.".kml";
         //  dump($mpath);
           if(file_exists($mpath))
            {
              $nrg++;
            }


          }
          $ward["rgcount"]= count($rgs);
          $ward["rgfound"]= $nrg;
          $wards[$i]=$ward;
        }
        $extraroadgroups =  $this->getDoctrine()->getRepository("App:Roadgroup")->findLooseRoadgroups();
        return $this->render('ward/showall.html.twig',
          [
            'rgyear'=>$this->rgyear,
            'message' =>  '' ,
            'heading' => 'The wards',
            'topmap'=>$topmap,
            'wards' => $wards,
            'roadgroups' => $extraroadgroups,
          ]);
    }


    public function showone($wdid)
    {
        $ward = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($wdid);
        if(!$this->mapserver->ismap($ward->getKML()))
        {
           $ward->setKML($this->mapserver->findmap($ward->getRggroupid()));
        }
        if (!$ward)
        {
            return $this->render('ward/showone.html.twig', [ 'message' =>  'ward not Found',]);
        }
        $subwards = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($wdid);

        $sglist = array();
        foreach ($subwards as $asubward)
        {
           $swid =  $asubward['rgsubgroupid'];
           $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swid);
           $rglist= array();
           foreach ($roadgroups as $aroadgroup)
           {
            if(!$this->mapserver->ismap($aroadgroup->getKML()))
            {
               $aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupid()));
            }
             $rglist[]=$aroadgroup->getKML();
           }
           $sglist[$swid]=$rglist;
        }
        $extraroadgroups =  $this->getDoctrine()->getRepository("App:Roadgroup")->findLooseRoadgroups();
        return $this->render('ward/showone.html.twig',
            [
                'rgyear'=>$this->rgyear,
                'message' =>  '' ,
                'ward'=> $ward,
                'subwards'=> $subwards,
                'roadgroups'=>$extraroadgroups,
                'sglist'=>$sglist,
                'back'=>"/rggroup/showall"
            ]);
    }



     public function edit($rgid)
    {
        $request = $this->requestStack->getCurrentRequest();
        if($rgid)
        {
            $ward = $this->getDoctrine()->getRepository('App:Rggroup')->findOne($rgid);
        }
        if(! isset($ward))
        {
            $ward = new Rggroup();
        }
        $form = $this->createForm(RggroupForm::class, $ward);
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ward);
                $entityManager->flush();
                $pid = $ward->getRggroupid();
                return $this->redirect("/rggroup/edit/".$rgid);
            }
        }

        return $this->render('ward/edit.html.twig', array(
            'rgyear'=>$this->rgyear,
            'form' => $form->createView(),
            'objid'=>$rgid,
            'ward'=>$ward,
            'returnlink'=>'/rggroup/problems',
            ));
    }



       public function delete($rgid)
    {

            $subward = $this->getDoctrine()->getRepository('App:Rggroup')->findOne($rgid);
            $wdid = $subward->getRggroupid();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($subward);
                $entityManager->flush();

                return $this->redirect("/rggroup/showall/");

    }

   public function newward()
    {
        $request = $this->requestStack->getCurrentRequest();
        $ward = new Rggroup();
        $form = $this->createForm(RggroupForm::class, $ward);
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
                return $this->redirect("/rggroup/showall");
            }
        }

        return $this->render('ward/edit.html.twig', array(
            'rgyear'=>$this->rgyear,
            'form' => $form->createView(),
            'ward'=>$ward,
            'returnlink'=>'/rggroup/showall',
            ));
    }

    public function showsubward($swdid)
    {
        $subward = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findOne($swdid);
        if (!$subward) {
            return $this->render('subward/showone.html.twig', [ 'message' =>  'subward not Found',]);
        }
        $wardid =  $subward->getRggroupid();
        $ward = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($wardid);
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swdid);
        foreach ($roadgroups as &$aroadgroup)
        {
           $kml = $aroadgroup->getKML();
            if(!$this->mapserver->ismap($kml))
           {
             $aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupId(),$this->rgyear));
           }
        }
       // $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets();
       $extrastreets =null;
        return $this->render('subward/showone.html.twig',
                              [
                                'rgyear'=>$this->rgyear,
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
            $subward = new Rgsubgroup();
            $subward->setRggroupid($wdid);
            $form = $this->createForm(NewRgsubgroupForm::class, $subward);
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($subward);
                $entityManager->flush();
                $pid = $subward->getRgsubgroupid();
                return $this->redirect("/rggroup/show/".$wdid);
            }
        }

        return $this->render('subward/new.html.twig', array(
            'rgyear'=>$this->rgyear,
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
            $subward = $this->getDoctrine()->getRepository('App:Rgsubgroup')->findOne($swdid);
        }
        if(! isset($subward))
        {
            $subward = new Rgsubgroup();
        }
            $form = $this->createForm(RgsubgroupForm::class, $subward);
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
                return $this->redirect("/rgsubgroup/show/".$swdid);
            }
        }

        return $this->render('subward/edit.html.twig', array(
            'rgyear'=>$this->rgyear,
            'form' => $form->createView(),
            'subward'=>$subward,
            'returnlink'=>'/street/problems',
            ));
    }


     public function DeleteSubward($swdid)
    {

            $subward = $this->getDoctrine()->getRepository('App:Rgsubgroup')->findOne($swdid);
            $wdid = $subward->getRggroupid();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($subward);
                $entityManager->flush();

                return $this->redirect("/rggroup/show/".$wdid);

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
