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

use App\Form\Type\WardForm;
use App\Form\Type\NewSubwardForm;
use App\Form\Type\SubwardForm;
use App\Entity\District;
use App\Entity\Ward;
use App\Entity\Subward;
use App\Entity\Roadgroup;

//use App\Service\PDF;
//use Fpdf\Fpdf;
//use Dompdf\Exception;

//require('fpdf.php');

class DistrictController extends AbstractController
{

    private $A4w = 210;
    private $A4h = 297;

    private $requestStack ;

    public function __construct( RequestStack $request_stack)
    {
        $this->requestStack = $request_stack;
    }





    public function showone($drid)
    {
        $district = $this->getDoctrine()->getRepository("App:District")->findOne($drid);
        if (!$district)
        {
            return $this->render('district/showone.html.twig', [ 'message' =>  'district not Found',]);
        }
        $seats = $this->getDoctrine()->getRepository("App:Seat")->findChildren($drid);
        return $this->render('district/showone.html.twig',
            [
                'message' =>  '' ,
                'district'=>$district,
                'seats'=>$seats,
                'back'=>"/"
            ]);
    }



     public function wardEdit($pid)
    {
        $request = $this->requestStack->getCurrentRequest();
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

    public function showsubward($swdid)
    {
        $subward = $this->getDoctrine()->getRepository("App:Subward")->findOne($swdid);
        if (!$subward) {
            return $this->render('subward/showone.html.twig', [ 'message' =>  'subward not Found',]);
        }
        $wardid =  $subward->getWardId();
        $ward = $this->getDoctrine()->getRepository("App:Ward")->findOne($wardid);
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swdid);
        $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets();
        return $this->render('subward/showone.html.twig',
                              [
                                'message' =>  '' ,
                                'ward'=>$ward,
                                'subward'=>$subward,
                                'roadgroups'=> $roadgroups,
                                 'streets'=>$extrastreets,
                                 'back'=>"/ward/show/".$wardid,
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
