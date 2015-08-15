<?php

namespace Heise\Shariff\Backend;

/**
 * Class Tumblr
 *
 * @package Heise\Shariff\Backend
 */
class Tumblr extends Request implements ServiceInterface
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'tumblr';
    }

    /**
     * @param string $url
     * @return \GuzzleHttp\Message\Request|\GuzzleHttp\Message\RequestInterface
     */
    public function getRequest($url)
    {
        return $this->createRequest('https://api.tumblr.com/v2/share/stats?url='.urlencode($url));
    }

    /**
     * @param array $data
     * @return int
     */
    public function extractCount(array $data)
    {
        return $data['response']['note_count'];
    }
}
