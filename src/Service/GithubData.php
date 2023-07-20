<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GithubData
{
    // Possible future evolution : Display number of commits by repo
    // $response = $this->client->request('GET', 'https://api.github.com/repos/leahad/cars4all/commits')
    public function __construct(private HttpClientInterface $client) {}

    public function getTotalContributions()
    {
        $response = $this->client->request('GET', 'https://github-contributions-api.deno.dev/leahad.json');

        $contributions = $response->toArray();
    
        return $contributions['totalContributions'];
    }
}