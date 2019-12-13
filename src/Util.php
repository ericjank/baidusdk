<?php

declare(strict_types=1);

/**
 * @license  https://github.com/ericjank/baidusdk/blob/master/LICENSE
 */

namespace BaiduAiSupport;

use GuzzleHttp\Client;

class Util
{
    /**
     * @var null|int
     */
    protected $timeout = 2;

    /**
     * @var null|object
     */
    public $client;


    public function __construct(array $config = []) {
        $this->timeout = isset($config['timeout']) ? $config['timeout'] : 2;
        $this->client = new Client([ 'timeout' => $this->timeout ]);
    }

    /**
     * 获取百度授权信息
     * @return array
     */
    public function getToken($client_id, $client_secret)
    {
        $token_key = '';

        $res = $this->client->request('GET', 'https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id=' . $client_id . '&client_secret=' . $client_secret);

        if ( $res->getStatusCode() == 200 ) {
            $token_content = $res->getBody();
            if ( $token_content) {
                $token_info = json_decode((string)$token_content, true);

                if (is_array($token_info)) {
                    return $token_info;
                }
            }
        }
        
        return false;
    }

}
