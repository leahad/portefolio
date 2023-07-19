<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[Vich\Uploadable]
class Project
{
    public const PROJECTS = [
    [
        "title"=> "CV de Hermione Granger", 
        "createdAt"=> "2023-03-07", 
        "duration" => "2 weeks", 
        "description" => "réaliser le CV d'un personnage fictif", 
        "skills" => ["Figma", "HTML" , "CSS" , "JavaScript" , "Git" , "Github"], 
        "github" =>"https://alexandre78r.github.io/CV-Hermione/", 
        "picture" => "CV-Hermione.jpg",
    ],
    [
        "title"=> "E-stoire", 
        "createdAt"=> "2023-04-05", 
        "duration" => "1 mois", 
        "description" => "plateforme collaborative de création d'histoires", 
        "skills" => ["Figma", "HTML", "CSS", "JavaScript", "Twig", "Pico", "PHP", "Composer", "SQL", "PDO",  "Git", "Github"], 
        "github" => "https://github.com/WildCodeSchool/2023-02-php-paris-p2-story", 
        "picture" => "e-stoire.jpg",
    ],
    [
        "title"=> "Hackathon : plan", 
        "createdAt"=> "2023-05-10", 
        "duration" => "1 jour", 
        "description" => "application de suggestion de destination de vacances", 
        "skills" => ["HTML", "SCSS", "Twig", "PHP", "Composer", "SQL", "PDO",  "Git", "Github"], 
        "github" => "https://github.com/leahad/plan", 
        "picture" => "plan.jpg",
    ],
    [
        "title"=> "Emmaüs Mobile Connect", 
        "createdAt"=> "2023-06-28", 
        "duration" => "2 jours", 
        "description" => "assistant de vente de smartphones pour Emmaüs", 
        "skills" => ["HTML", "CSS", "Twig", "Bootstrap", "PHP", "Symfony", "Composer", "SQL", "Yarn", "Git", "Github"], 
        "github" => "https://github.com/Lionel-darosa/Emmaus-Mobile-Connect", 
        "picture" => "plan.jpg",
    ],
    [
        "title"=> "Externatic", 
        "createdAt"=> "2023-05-28", 
        "duration" => "2 mois", 
        "description" => "plateforme de recherche d'emploi pour Externatic", 
        "skills" => ["Figma", "HTML", "CSS", "JavaScript", "Twig", "Bootstrap", "PHP", "Symfony", "Composer", "SQL", "Yarn", "Git", "Github"], 
        "github" => "https://github.com/WildCodeSchool/2023-02-php-paris-p3-externatic", 
        "picture" => "externatic.jpg",
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

    #[Vich\UploadableField(mapping: 'pictures', fileNameProperty: 'picture')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $pictureFile = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $video = null;

    #[ORM\ManyToMany(targetEntity: Skill::class, mappedBy: 'project')]
    private Collection $skills;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
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

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function setPictureFile(File $image = null): Project
    {
        $this->pictureFile = $image;
        if ($image) {
            $this->updatedAt = new DateTime('now');
        }

        return $this;
    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
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

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}
