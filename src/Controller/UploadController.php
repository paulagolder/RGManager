<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FileUploader;
use Psr\Log\LoggerInterface;

class UploadController extends AbstractController
{
    /**
     * @Route("/doUpload", name="do-upload")
     * @param Request $request
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @param LoggerInterface $logger
     * @return Response
     */
    public function index(Request $request, string $uploadDir,
                          FileUploader $uploader, LoggerInterface $logger): Response
    {
        $rgyear  = $request->cookies->get('rgyear');
        $token = $request->get("token");
        $rgid = $request->get("rgid");
        if (!$this->isCsrfTokenValid('upload', $token))
        {
            $logger->info("CSRF failure");

            return new Response("Operation not allowed",  Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);
        }
        $file = $request->files->get('fileToUpload');
        if (empty($file))
        {
            return new Response("No file specified",
               Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }
        $filename = $file->getClientOriginalName();
        $uploader->upload($uploadDir, $file, $filename."_".$rgyear);

        $this->addFlash(
            'notice',
            'Map '.$filename." dist:".$dist.' uploaded!'
        );
        $roadgroup = $this->getDoctrine()->getRepository('App:Roadgroup')->findOne($rgid);

        if(!$rgid )
           return $this->redirect("/");
        else
           return $this->redirect("/roadgroup/showone/".$rgid);
    }
}
