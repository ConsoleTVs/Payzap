<p align="center">
    <img src="http://i.imgur.com/Qk3UbZG.png">
    <h1 align="center">Payzap - Simple paypal payments for laravel</h1>
</p>

## Description

Payzap is the new simple way of integrating paypal in your laravel application with
a few lines of code. It uses simple and elegant syntax to create the payment.


## Installation

```
composer require consoletvs/payzap
```

Register the service provider to the current project (Not needed if using laravel 5.5+):

```php
ConsoleTVs\Payzap\PayzapServiceProvider::class
```

Publish the configuration:

```
php artisan vendor:publish
```

## Example payment controller & view

-   PaymentController.php

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ConsoleTVs\Payzap\Classes\Payment;

class PaymentController extends Controller
{
    /**
     * Returns the payment view and setups the javascript logic.
     *
     * @author Erik Campobadal <soc@erik.cat>
     * @copyright 2017 erik.cat
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment = Payment::prepare()
            ->createUrl(route('api::payments.create'))
            ->executeUrl(route('api::payments.execute'))
            ->redirectUrl(route('payment.finished'))
            ->buttonId('paypal-button');

        return view('payment', compact('payment'));
    }

    /**
     * Creates the paypal payment using payzap.
     *
     * @author Erik Campobadal <soc@erik.cat>
     * @copyright 2017 erik.cat
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public function create()
    {
        return Payment::create()
            ->addItem([
                'name' => 'Product 1',
                'currency' => 'EUR',
                'quantity' => 1,
                'price' => 0.5,
            ])->addItem([
                'name' => 'Product 2',
                'currency' => 'EUR',
                'quantity' => 2,
                'price' => 0.25,
            ])->description("My first payment")
            ->generate();
    }

    /**
     * Executes the paypal payment and returns the result boolean in an array.
     *
     * @author Erik Campobadal <soc@erik.cat>
     * @copyright 2017 erik.cat
     * @param  Request $request
     * @return array
     */
    public function execute(Request $request)
    {
        return ['result' => Payment::execute($request->payment_id, $request->payer_id)];
    }
}
```

-   payment.blade.php:

```html
<!DOCTYPE html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
</head>

<body>
    <div id="paypal-button"></div>
    {!! $payment->scripts() !!}
</body>
```

# Work in progress

Please wait a few hours before the librari is fully released and documented.
