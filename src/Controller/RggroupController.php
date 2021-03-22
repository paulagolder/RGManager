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
    $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
    if (!$rggroups)
    {
      return $this->render('ward/showone.html.twig', [  'rgyear'=>$this->rgyear, 'message' =>  'wards not Found',]);
    }
    // dump($rggroups);
    for($i=0; $i<count($rggroups); $i++)
    {
      $rggroup = $rggroups[$i];
      $rgs = $this->getDoctrine()->getRepository("App:Roadgroup")->findRoadgroupsinRGGroup($rggroup->getRggroupid(),$this->rgyear);
      $nrg = 0;
      $sumhh=0;
      for($j=0;$j<count($rgs);$j++)
      {
        $rgid = $rgs[$j]->getRoadgroupId();
        $hh = $rgs[$j]->getHouseholds();
        $sumhh += $hh;
      //  $mpath = $maproot."roadgroups/".$rgid.".kml";
        $kml = $rgs[$j]->getKML();
        if($kml)
        {
          $mpath = $maproot."roadgroups/".$kml;
          if(file_exists($mpath))
          {
            $rgs[$j]->setKML( $kml);
            $nrg++;
          }
        }

      }
      $rggroup->total= $sumhh;
      $rggroup->rgcount= count($rgs);
      $rggroup->rgfound= $nrg;
      $rggroup->roadgroups= $rgs;
      $rggroups[$i]=$rggroup;
    }
    $extraroadgroups =  $this->getDoctrine()->getRepository("App:Roadgroup")->findLooseRoadgroups();
    return $this->render('ward/showall.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'heading' => 'The wards',
    'topmap'=>$topmap,
    'rggroups' => $rggroups,
    'roadgroups' => $extraroadgroups,
    ]);
  }


  public function showone($wdid)
  {
    $rggroup = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($wdid);
    if (!$rggroup)
    {
      return $this->render('ward/showone.html.twig', [    'rgyear'=>$this->rgyear,'message' =>  'ward not Found',]);
    }
    if(!$this->mapserver->ismap($rggroup->getKML()))
    {
      $rggroup->setKML($this->mapserver->findmap($rggroup->getRggroupid(),$this->rgyear));
      $rggroup->setHouseholds($this->getDoctrine()->getRepository("App:Rggroup")->countHouseholds($rggroup->getRggroupid(), $this->rgyear));
    }
    $subwards = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($wdid);

    $sglist = array();
    foreach ($subwards as $asubward)
    {
      $swid =  $asubward->getRgsubgroupid();
      $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swid, $this->rgyear);
      $rglist= array();
      $totalhh=0;
      foreach ($roadgroups as $aroadgroup)
      {
        $totalhh += $aroadgroup->getHouseholds();
        if(!$this->mapserver->ismap($aroadgroup->getKML()))
        {
          $aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupid(),$this->rgyear));
        }
        $rglist[]=$aroadgroup->getKML();
      }
      $sglist[$swid]=$rglist;
      $asubward->total= $totalhh;
    }
    $extraroadgroups =  $this->getDoctrine()->getRepository("App:Roadgroup")->findLooseRoadgroups();
    return $this->render('ward/showone.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'ward'=> $rggroup,
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
      $rggroup = $this->getDoctrine()->getRepository('App:Rggroup')->findOne($rgid);
    }
    if(! isset($rggroup))
    {
      $rggroup = new Rggroup();
    }
    $form = $this->createForm(RggroupForm::class, $rggroup);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rggroup);
        $entityManager->flush();
        $pid = $rggroup->getRggroupid();
        return $this->redirect("/rggroup/edit/".$rgid);
      }
    }

    return $this->render('ward/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'objid'=>$rgid,
      'ward'=>$rggroup,
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
    $rggroup = new Rggroup();
    $form = $this->createForm(RggroupForm::class, $rggroup);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        // $person->setContributor($user->getUsername());
        // $person->setUpdateDt($time);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rggroup);
        $entityManager->flush();
        $pid = $rggroup->getRggroupid();
        return $this->redirect("/rggroup/showall");
      }
    }

    return $this->render('ward/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'form' => $form->createView(),
      'ward'=>$rggroup,
      'returnlink'=>'/rggroup/showall',
      ));
  }

  public function showsubward($swdid)
  {
    $subward = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findOne($swdid);
    if (!$subward) {
      return $this->render('subward/showone.html.twig', [   'rgyear'=>$this->rgyear,  'message' =>  'subward not Found',]);
    }
    $rggroupid =  $subward->getRggroupid();
    $rggroup = $this->getDoctrine()->getRepository("App:Rggroup")->findOne($rggroupid);
    $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swdid, $this->rgyear);
    foreach ($roadgroups as &$aroadgroup)
    {
      $kml = $aroadgroup->getKML();
      if(!$this->mapserver->ismap($kml))
      {
        $aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupId(),$this->rgyear));
      }
      $est = $this->getDoctrine()->getRepository("App:Roadgroup")->countHouseholds($aroadgroup->getRoadgroupId(),$this->rgyear);

      $aroadgroup->{"calculated"} = $est;

    }
    // $extrastreets =  $this->getDoctrine()->getRepository("App:Street")->findLooseStreets();
    $extrastreets =null;
    return $this->render('subward/showone.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'ward'=>$rggroup,
    'subward'=>$subward,
    'roadgroups'=> $roadgroups,
    'streets'=>$extrastreets,
    'back'=>"/rggroup/show/".$rggroupid,
    ]);
  }

  public function heatmap()
  {
    $mappath= $this->container->get('parameter_bag')->get('mappath');
    $maproot = $this->container->get('parameter_bag')->get('maproot');
    $topmap = $this->container->get('parameter_bag')->get('topmap');
    $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
    if (!$rggroups)
    {
      return $this->render('ward/showall.html.twig', [ 'message' =>  'RGgroups not Found',]);
    }
    $rglist= array();
    foreach($rggroups as $award)
    {
      $subwards = $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($award->getRggroupid());
      foreach ($subwards as $asubward)
      {
        $swid =  $asubward->getRgsubgroupid();
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($swid);
        foreach ($roadgroups as $aroadgroup)
        {
          if(!$this->mapserver->ismap($aroadgroup->getKML()))
          {
            $aroadgroup->setKML($this->mapserver->findmap($aroadgroup->getRoadgroupid(),$this->rgyear));
          }
          $labness = intval($aroadgroup->getPrioritygroup());
          $aroadgroup->setPrioritygroup($labness);
          $rg = array();
          $rg["kml"] = $aroadgroup->getKML();
          $rg["priority"] = $labness;
          $rg["color"] = $this->makeColor($labness);
          // dump($rg);
          $rglist[]=$rg;
        }
      }
    }
    return $this->render('ward/heatmap.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'rglist'=>$rglist,
    'back'=>"/rggroup/showall"
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




  /*      public function topdf($list)
   *    {
   *    $image = "./maps/BLY_C2.png";
   *    $file = "./maps/lichfielddc.pdf";
   * // Instanciation of inherited class
   * $pdf = new FPDF();
   * $pdf->AddPage();
   * $pdf->SetMargins(5, 5 , 5, 5);
   * $pdf->SetFont('Helvetica','',12);
   * $cellw =($this->A4w-(5+5+5))/2-4;
   * $cellh =($this->A4h-(5+5+5))/2-8;
   * $p=0;
   * while($p < count($list))
   * {
   * for($i=0;$i<=1;$i++)
   * {
   *  for($j=0;$j<=1;$j++)
   *  {
   *  $x = 5+$j*(5+$cellw);
   *  $y = 5+$i*(5+$cellh);
   *    $image = "./maps/".$list[p].".png";
   *  $pdf->setXY($x,$y);
   *    $pdf->Image($image,$x,$y,$cellw,$cellh);
   *  $pdf->Cell($cellw,$cellh,'',1,1,'C');
   *  $p++;
   *  $ip++;
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
    $rggroups = $this->getDoctrine()->getRepository("App:Rggroup")->findAll();
    if (!$rggroups) {
      return "";
    }
    foreach (  $rggroups as $award )
    {
      $subwards =   $this->getDoctrine()->getRepository("App:Rgsubgroup")->findChildren($award->getRggroupid());
      $award->subwards = $subwards;
      foreach($subwards as $asubward)
      {
        $roadgroups = $this->getDoctrine()->getRepository("App:Roadgroup")->findChildren($asubward->getRgsubgroupid());
        $asubward->roadgrouplist=$roadgroups;
        foreach($roadgroups as $aroadgroup)
        {
          $streets = $this->getDoctrine()->getRepository("App:Street")->findgroup($aroadgroup->getRoadgroupid(),$this->rgyear);

          $aroadgroup->{'streets'}= $streets;
        }
      }
    }

    foreach (  $rggroups as $award )
    {
      $xmlout .= $award->makexml();
      $xmlout .= "\n";
    }
    return $xmlout;;
  }


  function hexColor($color)
  {
    $rgb = dechex(($color[0]<<16)|($color[1]<<8)|$color[2]);
    return("#".substr("000000".$rgb, -6));
  }

  function makeColor($intval)
  {
    $r = 255;
    $b = 255;
    $g  = 255;
    if($intval==0)
    {
      $r = 0;
      $b = 0;
      $g  = 255;
    }else
    {
      if($intval>140)
      {
        $d= $intval -140;
        $r = 0;
        $b = 0;
        $g  =255;
      }else if($intval >70)
      {
        $r = 0;
        $b = 255;
        $g  = 0;
      }else
      {
        $d= $intval -140;
        $r = 255;
        $b = 0;
        $g  = 0;

      }

    }
    return $this->rgbToHex($r,$g,$b);
  }

  function componentToHex($c)
  {
    $hex = dechex($c);
    if( strlen($hex)< 2)
      return "0".$hex;
    else
      return $hex;
  }

  function rgbToHex($r, $g, $b)
  {
    return "#".$this->componentToHex($r).$this->componentToHex($g).$this->componentToHex($b);
  }



}

