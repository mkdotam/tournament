<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonController extends Controller
{

    public function jsonResponse($content, $status = Response::HTTP_OK)
    {
        $response = new Response();
        $response->setContent($content);
        $response->setStatusCode($status);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function jsonErrResponse($errorMessage, $status = Response::HTTP_BAD_REQUEST)
    {
        $response = new Response();
        $response->setContent($errorMessage);
        $response->setStatusCode($status);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}