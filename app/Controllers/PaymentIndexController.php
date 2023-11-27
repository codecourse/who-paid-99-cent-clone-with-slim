<?php

namespace App\Controllers;

use DI\Container;
use App\Models\Payment;
use App\Controllers\Controller;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PaymentIndexController extends Controller
{
    public function __invoke(Request $request, Response $response)
    {
        if (!$token = $request->getQueryParams()['token'] ?? false) {
            throw new HttpNotFoundException($request);
        }

        if (!Payment::whereToken($token)->first()) {
            throw new HttpNotFoundException($request);
        }

        $payments = Payment::latest()->get();

        return $this->container->get('view')->render($response, 'payments.twig', [
            'payments' => $payments,
            'messages' => $this->container->get('flash')->getMessages()
        ]);
    }
}
