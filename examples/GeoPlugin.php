<?php

namespace MASNathan\GeoPlugin;

use MASNathan\APICaller\Caller;

/**
 * GeoPlugin - API Wrapper for http://www.geoplugin.com/, Simple example on How to use the APIcaller class to call an
 * API
 *
 * @author    André Filipe <andre.r.flip@gmail.com>
 * @version   0.1.2
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
        $params = array(
            'ip'            => !$ip ? $_SERVER['REMOTE_ADDR'] : $ip,
            'base_currency' => $baseCurrency,
        );

        $response = $this->get('json.gp', $params);

        $data = $this->handleResponseContent($response, 'json');

        if ($renameArrayKeys) {
            $tmpData = array();
            foreach ($data as $key => $value) {
                $tmpData[str_replace('geoplugin_', '', $key)] = $value;
            }
            $data = $tmpData;
        }

        return $data;
    }

    public function test()
    {
        $response = $this->post('tests/http.php', json_encode(['foo' => 'bar']));

        $data = $this->handleResponseContent($response, 'json');

        dump($data);
        exit;
    }
}
