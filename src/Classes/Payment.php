<?php
/*
 * This file is part of consoletvs/payzap.
 *
 * (c) Erik Campobadal <soc@erik.cat>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ConsoleTVs\Payzap\Classes;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment as PaypalPayment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use ConsoleTVs\Payzap\Classes\Prepare;
use ConsoleTVs\Payzap\Traits\Setters;

/**
 * Payzap Payment class.
 *
 * @author Erik Campobadal <soc@erik.cat>
 */
class Payment
{
    use Setters;
    /**
     * Stores the payer object.
     *
     * @var \PayPal\Api\Payer
     */
    public $payer;

    /**
     * Store in an array the \PayPal\Api\Item objects.
     *
     * @var array
     */
    public $items = [];

    /**
     * Store the payment details.
     *
     * @var \PayPal\Api\Details
     */
    public $details;

    /**
     * Stores the payment currency.
     *
     * @var string
     */
    public $currency = "EUR";

    /**
     * Stores additional payment description.
     *
     * @var string
     */
    public $description = "";

    /**
     * Stores the return URL.
     *
     * @var string
     */
    public $return_url;

    /**
     * Stores the cancel URL.
     *
     * @var string
     */
    public $cancel_url;

    /**
     * Stores the paypal client id.
     *
     * @var string
     */
    protected $client_id;

    /**
     * Stores the paypal client secret.
     *
     * @var string
     */
    private $client_secret;

    /**
     * Setup the payment with the default values and API configuration.
     *
     * @author Erik Campobadal <soc@erik.cat>
     */
    public function __construct()
    {
        $this->payer([
            'paymentMethod' => 'paypal',
        ]);

        $this->clientId(config('payzap.API.client_id'));
        $this->clientSecret(config('payzap.API.client_secret'));
    }

    /**
     * Generates the payment and returns a valid paypal
     * payment creation response.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @return \PayPal\Api\Payment;
     */
    public function generate()
    {
        $items = new ItemList();
        $items->setItems($this->items);

        $sub_total = 0;
        foreach ($this->items as $item) {
            $sub_total += bcmul($item->getPrice(), $item->getQuantity(), 2);
        }

        if (!$this->details) {
            $this->details([]);
        }

        $this->details->setSubtotal($sub_total);

        $total = $sub_total + $this->details->getTax() + $this->details->getShipping();

        $amount = new Amount();
        $amount->setCurrency($this->currency)
            ->setTotal($total)
            ->setDetails($this->details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($items)
            ->setDescription($this->description)
            ->setInvoiceNumber(uniqid());

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($this->return_url ? $this->return_url : url('/'))
            ->setCancelUrl($this->cancel_url ? $this->cancel_url : url('/'));

        $payment = new PaypalPayment();
        $payment->setIntent("sale")
            ->setPayer($this->payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions([$transaction]);

        $api_context = new ApiContext(
            new OAuthTokenCredential(
                $this->client_id,
                $this->client_secret
            )
        );

        try {
            $payment->create($api_context);
        } catch (Exception $ex) {
            return false;
        }

        return $payment;
    }

    /**
     * Executes the payment based on the payment id and the payer id.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  int $payment_id
     * @param  int $payer_id
     * @param  string $client_id
     * @param  string $client_secret
     * @return bool
     */
    public static function execute($payment_id, $payer_id, $client_id = null, $client_secret = null)
    {
        if (!$client_id) {
            $client_id = config('payzap.API.client_id');
        }

        if (!$client_secret) {
            $client_secret = config('payzap.API.client_secret');
        }

        $api_context = new ApiContext(
            new OAuthTokenCredential(
                $client_id,
                $client_secret
            )
        );

        $payment = PaypalPayment::get($payment_id, $api_context);

        $execution = new PaymentExecution();
        $execution->setPayerId($payer_id);

        try {
            $payment->execute($execution, $api_context);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Creates a new client.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  array $data
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public static function create()
    {
        return new Payment();
    }

    /**
     * Prepares a new paypal payment.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @return \ConsoleTVs\Payzap\Classes\Prepare;
     */
    public static function prepare()
    {
        return new Prepare();
    }
}
