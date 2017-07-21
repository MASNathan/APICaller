<?php

namespace MASNathan\GeoPlugin;

use MASNathan\APICaller\Caller;

/**
 * GeoPlugin - API Wrapper for http://www.geoplugin.com/,
 * Simple example on How to use the APIcaller class to call an API
 *
 * @author    André Filipe <andre.r.flip@gmail.com>
 */
class GeoPlugin extends Caller
{

    /**
     * Fetches the IP locatio info
     *
     * @param string $ip
     * @param string $baseCurrency i.e.: "EUR"
     * @return array
     */
    public function getLocation($ip = '', $baseCurrency = '', $renameArrayKeys = false)
    {
        $params = [
            'ip'            => !$ip ? $_SERVER['REMOTE_ADDR'] : $ip,
            'base_currency' => $baseCurrency,
        ];

        $response = $this->client->get('json.gp', $params);

        $data = $this->handleResponseContent($response, 'json');

        if ($renameArrayKeys) {
            $tmpData = [];
            foreach ($data as $key => $value) {
                $tmpData[str_replace('geoplugin_', '', $key)] = $value;
            }
            $data = $tmpData;
        }

        return $data;
    }
}
