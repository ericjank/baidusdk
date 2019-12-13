<?php

declare(strict_types=1);

/**
 * @license  https://github.com/ericjank/baidusdk/blob/master/LICENSE
 */

namespace BaiduAiSupport;

use BaiduAiSupport\Util;

class Ai
{

    /**
     * 作诗
     * @param  string $text
     * @return array
     */
    public function poetry(string $text, string $access_token)
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
                return is_array($content) ? $content['poem'][0] : '';
            }
        }

        return false;
    }

}
