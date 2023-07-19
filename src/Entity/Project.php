<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    public const PROJECTS = [
    [
        "title"=> "CV de Hermione Granger", 
        "createdAt"=> "07/03/2023", 
        "duration" => "2 weeks", 
        "description" => "réaliser le CV d'un personnage fictif", 
        "skills" => ["Figma", "HTML" , "CSS" , "JavaScript" , "Git" , "Github"], 
        "github" =>"https://alexandre78r.github.io/CV-Hermione/", 
        "picture" => "CV-Hermione.jpg",
    ],
    [
        "title"=> "e-stoire", 
        "createdAt"=> "05/04/2023", 
        "duration" => "1 mois", 
        "description" => " plateforme collaborative de création d'histoires", 
        "skills" => ["Figma", "HTML" , "CSS" , "JavaScript" , "Twig" ,  "Pico" , "PHP",  "Composer" , "SQL"  , "PDO",  "Git" , "Github"], 
        "github" => "https://github.com/WildCodeSchool/2023-02-php-paris-p2-story", 
        "picture" => "e-stoire.jpg",
    ],

    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $Title = null;

    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    private ?string $duration = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $github = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $video = null;

    #[ORM\ManyToMany(targetEntity: Skill::class, mappedBy: 'project')]
    private Collection $skills;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getcreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setDate(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(string $github): static
    {
        $this->github = $github;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->addProject($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        if ($this->skills->removeElement($skill)) {
            $skill->removeProject($this);
        }

        return $this;
    }

}