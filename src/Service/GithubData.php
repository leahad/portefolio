<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\ProjectRepository;
use App\Entity\Project;

class GithubData
{
    // Possible future evolution : Display number of commits by repo
    // $response = $this->client->request('GET', 'https://api.github.com/repos/leahad/cars4all/commits')
    private ProjectRepository $projectRepository;

    public function __construct(private HttpClientInterface $client, ProjectRepository $projectRepository) {
        $this->projectRepository = $projectRepository;
    }

    // public function getTotalContributions()
    // {
    //     $response = $this->client->request('GET', 'https://github-contributions-api.deno.dev/leahad.json');

    //     $contributions = $response->toArray();
    
    //     return $contributions['totalContributions'];
    // }

    public function getLanguages()
    {
        $projects =  $this->projectRepository->findAll();
        $projectLanguages = [];
        

        foreach ($projects as $project) {
            $github = parse_url($project->getGithub());
            if (strpos($github['path'], 'git') == true) {
                $path = explode(".", $github['path']);
                $response = $this->client->request('GET', 'https://api.github.com/repos' . $path[0] . '/languages');
            } else {
                $response = $this->client->request('GET', 'https://api.github.com/repos' . $github['path'] . '/languages');
            }
        
            // $languages = array_splice($array,0,-2);
            // $language = implode(" - ", array_keys($newArray));
            // if (($key = array_search('Dockerfile', $languages)) !== false) {
            //     unset($languages[$key]);
            // }
            $languages = $response->toArray();
            $projectLanguages[$project->getId()] = $languages;
        
        }
        return $projectLanguages;
    }
    

    public function getBytesPercentage()
    {
        $bytes = array_values($this->getLanguages());
        $bytesPercent = [];
        foreach  ($bytes as $projectBytes) {
            $sumBytes = array_sum($projectBytes);
            foreach ($projectBytes as $byte) {
                $bytesPercent[] = round(($byte * 100) / $sumBytes);
            }
        }
        return $bytesPercent;
    }
}