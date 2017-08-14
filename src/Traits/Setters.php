<?php
/*
 * This file is part of consoletvs/payzap.
 *
 * (c) Erik Campobadal <soc@erik.cat>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ConsoleTVs\Payzap\Traits;

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\Details;

/**
 * Payzap setters trait.
 *
 * @author Erik Campobadal <soc@erik.cat>
 */
trait Setters
{
    /**
     * Set the payment currency.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  string $currency
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public function currency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Set the payment description.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  string $description
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the payment client_id.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  string $id
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public function clientId($id)
    {
        $this->client_id = $id;

        return $this;
    }

    /**
     * Set the payment client_secret.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  string $secret
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public function clientSecret($secret)
    {
        $this->client_secret = $secret;

        return $this;
    }

    /**
     * Adds an item to the payment item list.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  array $data
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public function addItem($data)
    {
        $item = new Item();

        foreach ($data as $key => $value) {
            $item->{'set' . ucfirst($key)}($value);
        }

        array_push($this->items, $item);

        return $this;
    }

    /**
     * Setup the payment payer information.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  array $data
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public function payer($data)
    {
        $payer = new Payer();

        foreach ($data as $key => $value) {
            $payer->{'set' . ucfirst($key)}($value);
        }

        $this->payer = $payer;

        return $this;
    }

    /**
     * Setup the payment details.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  array $data
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public function details($data)
    {
        $details = new Details();

        foreach ($data as $key => $value) {
            $details->{'set' . ucfirst($key)}($value);
        }

        $this->details = $details;

        return $this;
    }

    /**
     * Setup the payment return_url.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  string $url
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public function returnUrl($url)
    {
        $this->return_url = $url;

        return $this;
    }

    /**
     * Setup the payment cancel_url.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  string $url
     * @return \ConsoleTVs\Payzap\Classes\Payment
     */
    public function cancelUrl($url)
    {
        $this->cancel_url = $url;

        return $this;
    }
}
