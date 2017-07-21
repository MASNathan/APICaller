<?php

namespace MASNathan\GeoPlugin;

use MASNathan\APICaller\Client;

/**
 * Geoplugin - API Wrapper for http://www.geoplugin.com/,
 * Simple example on How to use the APIcaller class to call an API
 *
 * @author    André Filipe <andre.r.flip@gmail.com>
 */
class GeoPluginClient extends Client
{
    public function __construct()
    {
        parent::__construct();

        $this->setHeaders([
            'Accept' => 'application/json',
        ]);
    }

    public function getEndpoint()
    {
        return 'http://www.geoplugin.net/';
    }
}
