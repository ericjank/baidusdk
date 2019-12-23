<?php

declare(strict_types=1);

/**
 * @license  https://github.com/ericjank/baidusdk/blob/master/LICENSE
 */

namespace BaiduAiSupport;

use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\ApplicationContext;

class Util
{
    /**
     * @var null|int
     */
    protected $timeout = 5;

    /**
     * @var null|object
     */
    public $client;

    /**
     * @var \Hyperf\Guzzle\ClientFactory
     */
    private $clientFactory;

    public function __construct(array $config = []) {
        $this->timeout = isset($config['timeout']) ? $config['timeout'] : 5;


        $this->clientFactory = new ClientFactory(ApplicationContext::getContainer());
        $this->client = $this->clientFactory->create($config);
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
