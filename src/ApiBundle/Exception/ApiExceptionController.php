<?php

namespace ApiBundle\Exception;


use ApiBundle\Controller\JsonController;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ApiExceptionController extends JsonController
{
    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        return $this->jsonErrResponse($exception->getMessage());
    }
}