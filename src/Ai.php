<?php

declare(strict_types=1);

/**
 * @license  https://github.com/ericjank/baidusdk/blob/master/LICENSE
 */

namespace BaiduAiSupport;

use BaiduAiSupport\Util;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Utils\ApplicationContext;

class Ai
{

    /**
     * 作诗
     * @param  string $text
     * @return array
     */
    static public function poetry(string $text, string $access_token)
    {
        $util = new Util();

        $content = '';
        $res = $util->client->request('POST', 'https://aip.baidubce.com/rpc/2.0/nlp/v1/poem?access_token=' . $access_token, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'text' => $text, 
                'index' => 0
            ])
        ]);

        if ( $res->getStatusCode() == 200 ) {
            $content = $res->getBody();
            if ( $content) {
                $content = json_decode((string)$content, true);

                if ( is_array($content) && isset($content['poem'][0]) )
                {
                    return $content['poem'][0];
                }

                $logger = ApplicationContext::getContainer()->get(StdoutLoggerInterface::class);
                $logger->error(sprintf('[%s] Failed %s.', "调用写诗接口 ", json_encode($content)));
                return isset($content['error_code']) ? $content['error_code'] : '';
            }
        }

        return false;
    }

    /**
     * 人脸融合
     * @param  string $text
     * @return array
     * @throws [type] [<description>]
     */
    static public function faceMerge(string $targetCode, string $tempCode, string $access_token)
    {
        $util = new Util();

        $content = '';
        $res = $util->client->request('POST', 'https://aip.baidubce.com/rest/2.0/face/v1/merge?access_token=' . $access_token, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'image_template' => [
                    'image' => $tempCode,
                    'image_type' => 'BASE64',
                    'quality_control' => 'NORMAL',
                ], 
                'image_target' => [
                    'image' => $targetCode,
                    'image_type' => 'BASE64',
                    'quality_control' => 'NORMAL'
                ],
            ])
        ]);

        if ( $res->getStatusCode() == 200 ) {
            $content = $res->getBody();
            if ( $content) {
                $content = json_decode((string)$content, true);

                if ( is_array($content) && $content['error_code'] == 0 )
                {
                    return $content;
                }

                $logger = ApplicationContext::getContainer()->get(StdoutLoggerInterface::class);
                $logger->error(sprintf('[%s] Failed %s.', "调用人脸融合接口 ", json_encode($content)));
                
                return $content;
            }
        }

        return false;
    }

}
