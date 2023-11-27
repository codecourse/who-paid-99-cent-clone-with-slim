<?php

use App\Controllers\HomeController;
use App\Controllers\PaymentIndexController;
use App\Controllers\PaymentStoreController;

$app->get('/', HomeController::class);

$app->post('/payments', PaymentStoreController::class);
$app->get('/payments', PaymentIndexController::class);