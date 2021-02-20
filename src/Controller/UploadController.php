<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FileUploader;
use Psr\Log\LoggerInterface;

use App\Service\MapServer;




class UploadController extends AbstractController
{

    private $mapserver;



    /**
     * @Route("/doUpload", name="do-upload")
     * @param Request $request
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @param LoggerInterface $logger
     * @return Response
     */
    public function index(Request $request, string $uploadDir,
                          FileUploader $uploader, LoggerInterface $logger, MapServer $mapserver): Response
    {

        $rgyear  = $request->cookies->get('rgyear');
        $token = $request->get("utoken");
        $rgid = $request->get("rgid");
         $this->mapserver = $mapserver;
         $this->mapserver->load(true);
       /* if (!$this->isCsrfTokenValid('upload-map', $token))
        {
            $logger->info("CSRF failure");
            return new Response("Operation not allowed",  Response::HTTP_BAD_REQUEST, ['content-type' => 'text/plain']);
        }*/
        $file = $request->files->get('fileToUpload');
        if (empty($file))
        {
            return new Response("No file specified",
               Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }
         $filename = $file->getClientOriginalName();
         if(!str_contains($filename, "_20"))
         {
           $filename = str_replace( ".kml", "_".$rgyear.".kml",$filename);
         }
        $uploader->upload($uploadDir, $file, $filename);

        $this->addFlash(
            'notice',
            'Map '.$filename.' uploaded!'.$token
        );
        $roadgroup = $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid);
        $this->mapserver = $mapserver->load();
        if(!$rgid )
           return $this->redirect("/");
        else
           return $this->redirect("/roadgroup/showone/".$rgid);
    }
}
