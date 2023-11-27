<?php

namespace App\Controllers;

use DI\Container;
use App\Models\Payment;
use App\Controllers\Controller;
use Stripe\Exception\CardException;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PaymentStoreController extends Controller
{
    public function __invoke(Request $request, Response $response)
    {
        ['name' => $name, 'email' => $email, 'payment_method' => $paymentMethod] = $request->getParsedBody();

        try {
            $charge = $this->container->get('stripe')->paymentIntents->create([
                'amount' => 99,
                'currency' => 'usd',
                'payment_method' => $paymentMethod,
                'description' => 'Who paid 99¢ payment',
                'confirm' => true,
                'receipt_email' => $email
            ]);
    
            Payment::create([
                'name' => $name,
                'email' => $email,
                'token' => $token = bin2hex(random_bytes(32)),
                'stripe_id' => 'abc'
            ]);

            $this->container->get('flash')
                ->addMessage('status', 'Payment successful.');

            return $response->withHeader('Location', '/payments?token=' . $token);
        } catch (CardException $e) {
            $this->container->get('flash')
                ->addMessage('status', 'There was a problem processing your payment.');

            return $response->withHeader('Location', '/');
        }
    }
}
