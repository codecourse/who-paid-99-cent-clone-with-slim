<?php

namespace App\Controllers;

use DI\Container;
use App\Models\Payment;
use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController extends Controller
{
    public function __invoke(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'home.twig', [
            'messages' => $this->container->get('flash')->getMessages()
        ]);
    }
}
