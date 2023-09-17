<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\ProjectRepository;
use App\Entity\Project;

class GithubData
{
    const LANGUAGES = [
        1 => ['PHP' => 25000, 'JS'=>  5000,'TWIG'=>  20000],
        2 => ['PHP' => 40000, 'SCSS'=>  5000,'TWIG'=>  20000,],
        3 => ['PHP' => 40000, 'JS'=>  5000,'HTML'=>  20000,],
        4 => ['JAVA' => 40000, 'JS'=>  5000,'HTML'=>  20000,],
        5 => ['CSS' => 40000, 'JS'=>  5000,'REACT'=>  20000,],
    ];

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

    public function getLanguagesWithPercentage()
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
        
            $languages = $response->toArray();
        
            foreach ($languages as $language => $bytes) {
                if ($bytes < 2000) {
                    unset($languages[$language]);
                }
            }
                
            $sumBytes = array_sum($languages);
            $percentages = [];
            foreach ($languages as $language => $bytes) {
                $percentage = ($sumBytes > 0) ? ceil(($bytes * 100) / $sumBytes) : 0;
                $percentages[$language] = $percentage;
            }

            $projectLanguages[$project->getId()] = $percentages;
        }

        // $projectLanguages = SELF::LANGUAGES;
        // dd($projectLanguages);
        return $projectLanguages;
    }
    
}