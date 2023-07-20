<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FortuneCookie
{
    public function __construct(private HttpClientInterface $client) {}

    public function getMessage()
    {
        
        $response = $this->client->request('GET', 'https://raw.githubusercontent.com/reggi/fortune-cookie/master/fortune-cookie.json');

        $messages = $response->toArray();
    
        return $messages[array_rand($messages)];
    }
}