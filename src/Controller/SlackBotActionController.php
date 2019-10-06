<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SlackBotActionController extends AbstractController
{
    /**
     * @Route("/slack/actions")
     */
    public function action(Request $request, LoggerInterface $logger)
    {
        $logger->log(LogLevel::DEBUG, var_export($request->request->all(), true));

        return new JsonResponse([]);
    }
}