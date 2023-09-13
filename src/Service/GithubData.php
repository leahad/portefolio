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

    public function getTotalContributions()
    {
        $response = $this->client->request('GET', 'https://github-contributions-api.deno.dev/leahad.json');

        $contributions = $response->toArray();
    
        return $contributions['totalContributions'];
    }

    public function getLanguages()
    {
        $projects =  $this->projectRepository->findAll();
        foreach ($projects as $project) {
            $github = parse_url($project->getGithub());
        }

        $response = $this->client->request('GET', 'https://api.github.com/repos' . $github['path'] . '/languages');
        $languages = $response->toArray();
        // $newArray = array_splice($languages,0,-2);
        // $language = implode(" - ", array_keys($newArray));
        // return $languages;
    }
}