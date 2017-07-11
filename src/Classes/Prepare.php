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

/**
 * Payzap Payment class.
 *
 * @author Erik Campobadal <soc@erik.cat>
 */
class Prepare
{
    /**
     * The create URL specify the API URL used to create the payment.
     *
     * @var string
     */
    public $create_url = "";

    /**
     * The execute URL specify the API URL used to execute the payment.
     *
     * @var string
     */
    public $execute_url = "";

    /**
     * The redirect URL specify the API URL where the user will get redirected
     * if the payment was completed.
     *
     * @var string
     */
    public $redirect_url = "";

    /**
     * The button ID is the HTML id of where the button will be rendered.
     *
     * @var string
     */
    public $button_id = "";

    /**
     * Setup the payment preparation with the default config values.
     *
     * @author Erik Campobadal <soc@erik.cat>
     */
    public function __construct()
    {
        $this->createUrl(config('payzap.prepare.create_url'));
        $this->executeUrl(config('payzap.prepare.execute_url'));
        $this->redirectUrl(config('payzap.prepare.redirect_url'));
        $this->buttonId(config('payzap.prepare.button_id'));
    }

    /**
     * Set the create_url attribute.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  string $create_url
     * @return \ConsoleTVs\Payzap\Classes\Prepare
     */
    public function createUrl($create_url)
    {
        $this->create_url = $create_url;

        return $this;
    }

    /**
     * Set the execute_url attribute.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  string $execute_url
     * @return \ConsoleTVs\Payzap\Classes\Prepare
     */
    public function executeUrl($execute_url)
    {
        $this->execute_url = $execute_url;

        return $this;
    }

    /**
     * Set the redirect_url attribute.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  string $redirect_url
     * @return \ConsoleTVs\Payzap\Classes\Prepare
     */
    public function redirectUrl($redirect_url)
    {
        $this->redirect_url = $redirect_url;

        return $this;
    }

    /**
     * Set the button_id attribute.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @param  string $button_id
     * @return \ConsoleTVs\Payzap\Classes\Prepare
     */
    public function buttonId($button_id)
    {
        $this->button_id = $button_id;

        return $this;
    }

    /**
     * Generates and returns the scripts to create the payment button and
     * all the javascript logic.
     *
     * @author Erik Campobadal <soc@erik.cat>
     *
     * @return string
     */
    public function scripts()
    {
        return view('payzap::scripts.paypal', [
            'create_url' => $this->create_url,
            'execute_url' => $this->execute_url,
            'redirect_url' => $this->redirect_url,
            'button_id' => $this->button_id,
        ]);
    }
}
