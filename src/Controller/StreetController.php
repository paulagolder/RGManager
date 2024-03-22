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

use App\Service\MapServer;
use App\Form\Type\StreetForm;
use App\Entity\Street;
use App\Entity\Geodata;
use App\Entity\Roadgrouptostreet;


class StreetController extends AbstractController
{

  private $requestStack ;
  private $rgyear ;
  private $mapserver ;

  public function __construct( RequestStack $request_stack,  MapServer $mapserver)
  {
    $this->requestStack = $request_stack;
    $mapserver->load();
    $this->mapserver = $mapserver;
    $this->rgyear  = $this->requestStack->getCurrentRequest()->cookies->get('rgyear');
  }

  public function Showall()
  {
    $streets = $this->getDoctrine()->getRepository("App:Street")->findAll();
    //$this->fixPath($streets);
    if (!$streets)
    {
      return $this->render('street/showall.html.twig', [ 'message' =>  'Streets not Found',]);
    }

    return $this->render('street/showall.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'heading' => 'The streets',
    'streets'=> $streets,
    ]);

  }

  public function filters()
  {
    $message= null;
    if (isset($_POST['filter']))
    {
      $filter = ($_POST['filter']);
    }else
      $filter= "all";

    switch ($filter)
    {
      case  "duplicates":
        $streets = $this->getDoctrine()->getRepository("App:Street")->findDuplicates();
        foreach($streets as &$street)
        {
        $street->fixPath();
        }
        break;
      case  "split":
        $streets = $this->getDoctrine()->getRepository("App:Street")->findSplit();
        foreach($streets as &$street)
        {
          $street->fixPath();
        }
        break;
      case  "new":
        $streets = $this->getDoctrine()->getRepository("App:Street")->findNew();
        foreach($streets as &$street)
        {
          $street->fixPath();
        }
        break;

      case  "nopath":
        return $this->ShowProblems('np');
      case  "problem":
        return $this->ShowProblems('rg');
      case  "nogeodata":
        return $this->ShowProblems('ge');
      default :
        $streets = $this->getDoctrine()->getRepository("App:Street")->findAll();
        $this->fixPath($streets);
        break;
    }

    if (!$streets) {
      return $this->render('street/showall.html.twig', [ 'message' =>  'Streets not Found',]);
    }

    return $this->render('street/showproblems.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  $message ,
    'heading' => 'The streets',
    'streets'=> $streets,
    ]);

  }


  public function ShowProblems($problemtype='')
  {
    if($problemtype == "pd")
    {
    $streets = $this->getDoctrine()->getRepository("App:Street")->findProblems($problemtype);
    }
    if($problemtype == "rg")
    {
      $streets = $this->getDoctrine()->getRepository("App:Street")->findNoRoadgroup( $this->rgyear);
    }
    if($problemtype == "np")
    {
      $streets = $this->getDoctrine()->getRepository("App:Street")->findProblems($problemtype);
    }
    if($problemtype == "ge")
    {
      $streets = $this->getDoctrine()->getRepository("App:Street")->findProblems($problemtype);
      $entityManager = $this->getDoctrine()->getManager();
      foreach($streets as $street)
      {
        $street->makeGeodata();
        $entityManager->persist($street);
      }
      $entityManager->flush();
      $entityManager->flush();
        $astreet;
      }


    if (!$streets) {
      return $this->render('street/showproblems.html.twig', [ 'message' =>  'Streets not Found',]);
    }
    foreach($streets as &$astreet)
    {
      // $astreet = $this->getDoctrine()->getRepository('App:Street')->findOneBySeq($seq);
      $astreet->fixPath();
      $astreet->makeGeodata();
      $roadgroupid =  $this->getDoctrine()->getRepository('App:Roadgrouptostreet')->getRoadgroup($astreet->getSeq(),$this->rgyear);
      $astreet->roadgroupid = $roadgroupid;
    }

    return $this->render('street/showproblems.html.twig',
    [
    'rgyear'=>$this->rgyear,
    'message' =>  '' ,
    'heading' => 'Problem Streets',
    'streets'=> $streets,
    'filter'=>$problemtype,
    ]);
  }

  public function StreetViewGroupbyName($rdname)
  {

    $streets = $this->getDoctrine()->getRepository('App:Street')->findAllbyName($rdname);
    foreach($streets as &$astreet)
    {
     // $astreet = $this->getDoctrine()->getRepository('App:Street')->findOneBySeq($seq);
      $astreet->fixPath();
      $astreet->makeGeodata();
      $roadgroupid =  $this->getDoctrine()->getRepository('App:Roadgrouptostreet')->getRoadgroup($astreet->getSeq(),$this->rgyear);
      $astreet->roadgroupid = $roadgroupid;
    }
    return $this->render('street/viewgroup.html.twig', array(
      'rgyear'=>$this->rgyear,
      'message'=>"",
      'streets'=>$streets,
      'back'=>'/street/showall',
      ));
  }

  public function StreetViewGroupbySeq($rdseq)
  {

    $street = $this->getDoctrine()->getRepository('App:Street')->findOnebySeq($rdseq);
    return $this->StreetViewGroupbyName($street->getName());
  }

  public function Search()
  {
    $searchfield = ($_POST['searchfield']);
    $streets = $this->getDoctrine()->getRepository('App:Street')->namesearch($searchfield);
    $this->fixPath($streets);
    return $this->render('street/showall.html.twig', array(
      'rgyear'=>$this->rgyear,
      'message'=>"",
      'streets'=>$streets,
      'back'=>'/street/showall',
      ));
  }

  public function StreetGroupOps()
  {

    if (isset($_POST['Merge']))
    {
      $streetlist = ($_POST['selectstreets']);
      $nstreet = new Street();
      foreach($streetlist as $seq)
      {
        $astreet = $this->getDoctrine()->getRepository('App:Street')->findOneBySeq($seq);
        $astreet->fixPath();
        $nstreet->merge($astreet);
      }

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($nstreet);
      $entityManager->flush();
      $gstreetid = $nstreet->getSeq();
      return $this->redirect("/street/editgroup/".$gstreetid);
    }
    if (isset($_POST['Delete']))
    {
      $streetlist = ($_POST['selectstreets']);
      $entityManager = $this->getDoctrine()->getManager();
      $gstreet = "";
      foreach($streetlist as $seq)
      {
        $astreet = $this->getDoctrine()->getRepository('App:Street')->findOnebySeq($seq);
        $gstreet = $astreet->getName();
        $entityManager->remove($astreet);
      }
      $entityManager->flush();
      return $this->redirect("/street/viewgroup/".$gstreet);

    }

  }

  public function StreetEdit($rdseq)
  {

    $request = $this->requestStack->getCurrentRequest();
    if(!$rdseq) return $this->redirect("/rggroup/showall");
    $astreet = $this->getDoctrine()->getRepository('App:Street')->findOnebySeq($rdseq);
     $geodata = new Geodata();;
    $pdid = $astreet->getPdId();
    if($pdid)
    {
       $pd = $this->getDoctrine()->getRepository('App:Pollingdistrict')->findOne($pdid);
         $geodata = $pd->getGeodata_obj();
     }
         if(!$geodata or !$geodata->isgeodata())
         {
           $seatid = $this->getDoctrine()->getRepository('App:Seat')->findSeatsfromPD($pdid);
           if(count($seatid)>0)
           {
             $seat= $this->getDoctrine()->getRepository('App:Seat')->findone($seatid[0]["seatid"]);
             $geodata = $seat->getGeodata();
           }
        }
    $rgid= $this->getDoctrine()->getRepository('App:Roadgrouptostreet')->findRg($rdseq,$this->rgyear);
    if($rgid)
      $roadgroup =  $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid,$this->rgyear);
    else
      $roadgroup = null;
    $rggroup = null;
    if($roadgroup)
    {
      $rggroup =   $this->getDoctrine()->getRepository('App:Rggroup')->findOne($roadgroup->getRggroupid());
    }
    $streets = $this->getDoctrine()->getRepository('App:Street')->findAllbyName($astreet->getName());


    $path = $astreet->getDecodedPath();
    $tracks=$path;
    $streetcount = sizeof($streets);
    if(! isset($astreet))
    {
      $astreet = new Street();
    }
    $form = $this->createForm(StreetForm::class, $astreet);
    if ($request->getMethod() == 'POST')
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        try{
        $path = $astreet->getDecodedPath();
        $tracks=$path;
        $newtracks =[];
        foreach($tracks as $track)
        {
          if($track->steps != "[]")
          {
          if(count($track->steps)>1)  $newtracks[] = $track;
          }
        }
        $newpath = json_encode($newtracks);
        $geodata = $this->mapserver->make_geodata_steps_obj($newtracks);
        $time = new \DateTime();
        $astreet->setUpdated($time);
        $astreet->setPath($newpath);
        $astreet->setGeodata($geodata);
        if($astreet->getPart() == null)$astreet->setPart("");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($astreet);
        $entityManager->flush();
        $rdid = $astreet->getStreetId();
       return $this->redirect( " /pollingdistrict/showstreets/$pdid");
        }
        catch (PDOException $e)
        {
           return $this->redirect( " /pollingdistrict/showstreets/$pdid");
        }


      }
    }

    return $this->render('street/edit.html.twig', array(
      'rgyear'=>$this->rgyear,
      'roadgroupid' =>$rgid,
      'form' => $form->createView(),
      'streetcount'=>$streetcount,
      'streets'=>$streets,
      'street'=>$astreet,
      'tracks'=>$tracks,
      'geodata'=> $geodata,
      'back'=>"/street/viewgroup/$rdseq",
      ));
  }

  public function StreetDelete($stseq)
  {
    $astreet = $this->getDoctrine()->getRepository('App:Street')->findOnebySeq($stseq);
    $pdid = $astreet->getPdId();
    if($astreet==null)  return;
   //    $astreet->fixPath();
    $back =  "/pollingdistrict/show/".$pdid;
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($astreet);
    $entityManager->flush();
    return $this->redirect($back);
  }


  public function StreetImport()
  {

    $message = '';
    if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload')
    {
      if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK)
      {
        // get details of the uploaded file
        $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
        $fileName = $_FILES['uploadedFile']['name'];
        $fileSize = $_FILES['uploadedFile']['size'];
        $fileType = $_FILES['uploadedFile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // sanitize file-name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // check if file has one of the following extensions
        $allowedfileExtensions = array('csv');

        if (in_array($fileExtension, $allowedfileExtensions))
        {
          // directory in which the uploaded file will be moved
          $uploadFileDir = './uploaded_files/';
          $dest_path = $uploadFileDir . $newFileName;

          if(move_uploaded_file($fileTmpPath, $dest_path))
          {
            $this->addFlash('notice','csv file loaded '.$dest_path);
            $uploadedfile = file_get_contents($dest_path);
          }
          else
          {
            $this->addFlash('There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.');
          }
        }
        else
        {
          $this->addFlash('Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions));
        }
      }
      else
      {
        $this->addFlash('There is some error in the file upload. Please check the following error.<br>');
        $this->addFlash( 'Error:' . $_FILES['uploadedFile']['error']);
      }
    }

    $lineNumber = 1;
    $lines = explode(PHP_EOL, $uploadedfile);
    // Iterate over every line of the file
   foreach($lines as $line)
    {
      $row = str_getcsv($line);
      if($row[0]!=null)
      {

      $streets = $this->getDoctrine()->getRepository('App:Street')->findAllbyNamePd($row[3],$row[1]);
   if(count($streets)>1)
   {

     foreach($streets as $street)
   {
      //dump($street->getName());
   }
    }if(count($streets) == 0)
    {

      dump(" NOT FOUND ");
      $astreet =new Street();
      $astreet->setName($row[3]);
      $astreet->setQualifier("NEW");
      $astreet->setPD($row[1]);
      $astreet->setElectors($row[4]);
      $astreet->setHouseholds($row[5]);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($astreet);
      $entityManager->flush();
    }
    else
    {
      $astreet =$streets[0];
      $astreet->setElectors($row[4]);
      $astreet->setHouseholds($row[5]);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($astreet);
      $entityManager->flush();
    }
      }
      $lineNumber++;
    }
    return $this->redirect("/street/showall");
  }

  public function StreetReplicate($stseq)
  {
    if($stseq)
    {
      $astreet = $this->getDoctrine()->getRepository('App:Street')->findOnebySeq($stseq);
      $astreet->fixPath();
      if(!$astreet) return $this->redirect("/street/showall");
    }
    else
      return $this->redirect("/street/editgroup/".$astreet->getSeq());
    $nstreet = new Street();
    $nstreet->merge($astreet);
    $nstreet->setSeq(0);
    $astreet->setPart($astreet->getPart() ."a");
    $astreet->setUpdated(null);
    $astreet->setNote($astreet->getNote() ." Review Household Count");
    $nstreet->setPart($nstreet->getPart() ."b");
    $nstreet->setNote($astreet->getNote() ." Review Household Count");
    $nstreet->setUpdated(null);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($astreet);
    $entityManager->persist($nstreet);
    $entityManager->flush();
    //now change roadgroupto street to match
    //
    return $this->redirect("/street/editgroup/".$astreet->getSeq());
  }




  public function StreetRemove($rgid,$rdseq)
  {

    $this->getDoctrine()->getRepository('App:Roadgrouptostreet')->remove($rgid,$rdseq,$this->rgyear);

    return $this->redirect("/roadgroup/showone/".$rgid);
  }

  public function StreetAdd($rgid)
  {

    $stid = $_POST["selstreet"];
    if($stid)
    {
      $astreet = $this->getDoctrine()->getRepository('App:Street')->findOnebySeq($stid);
      $astreet->fixPath();
    }
    else
      return;

    $this->getDoctrine()->getRepository('App:Roadgroup')->addStreet($astreet,$rgid,$this->rgyear);

    return $this->redirect("/roadgroup/showone/".$rgid);
  }


  function fixPath($streets)
  {
    foreach($streets as $street)
    {
      $street->setPath( $street->getpath());
    }
    return $streets;
  }
}
