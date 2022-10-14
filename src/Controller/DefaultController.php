<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    public function index()
    {
        /**
            @Route("/")
        */
        return new Response('Hello world!');
    }
}
