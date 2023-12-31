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
        "mockup" => "",
        "skills" => ["Figma", "HTML" , "CSS" , "JavaScript"], 
        "commits" => 149,
        "github" => "https://github.com/Alexandre78R/CV-Hermione.git", 
        "picture" => "CV-Hermione.png",
        "video" => "",
        "link" => "https://alexandre78r.github.io/CV-Hermione/",
    ],
    [
        "title"=> "E-stoire", 
        "createdAt"=> "2023-04-05", 
        "duration" => "1 mois", 
        "description" => "plateforme collaborative de création d'histoires", 
        "mockup" => "https://www.figma.com/file/EYlWCKe1G4c8EGyg4NcYNE/e-stoires?type=design&node-id=0-1&mode=design&t=jkmHRCLcYSAC1FYe-0",
        "skills" => ["Figma", "HTML", "CSS", "JavaScript", "Twig", "Pico", "PHP", "PDO"],
        "commits" => 167,
        "github" => "https://github.com/WildCodeSchool/2023-02-php-paris-p2-story", 
        "picture" => "e-stoires.png",
        "video" => "https://www.loom.com/embed/c23040421c2546f8b53af4e2f537a04d?sid=e861b0f3-631c-4b0d-9428-e9dbbbbeea1b?hide_owner=true&hide_share=true&hide_title=true&hideEmbedTopBar=true.",
        "link" => "",
    ],
    [
        "title"=> "Hackathon : plan", 
        "createdAt"=> "2023-05-10", 
        "duration" => "1 jour", 
        "description" => "application de suggestion de destination de vacances", 
        "mockup" => "",
        "skills" => ["HTML", "SCSS", "Twig", "PHP", "SQL", "PDO"], 
        "commits" => 0,
        "github" => "https://github.com/leahad/plan", 
        "picture" => "plan.png",
        "video" => "https://www.loom.com/embed/552be33b5a204860a383781bf42acdcb?sid=1502ff2d-864f-40f2-82d6-d1bd911daf2d?hide_owner=true&hide_share=true&hide_title=true&hideEmbedTopBar=true.",
        "link" => "",
    ],
    [
        "title"=> "Emmaüs Mobile Connect", 
        "createdAt"=> "2023-06-28", 
        "duration" => "2 jours", 
        "description" => "assistant de vente de smartphones pour Emmaüs", 
        "commits" => 125,
        "mockup" => "",
        "skills" => ["HTML", "CSS", "Twig", "Bootstrap", "PHP", "Symfony"], 
        "github" => "https://github.com/Lionel-darosa/Emmaus-Mobile-Connect", 
        "picture" => "emmaus.png",
        "video" =>"https://www.loom.com/embed/a952e632496e45788dcad61332cc5e5c?sid=31949e03-5ce8-4cf2-897f-ece96c4deb29?hide_owner=true&hide_share=true&hide_title=true&hideEmbedTopBar=true.",
        "link" => "",
    ],
    [
        "title"=> "Externatic", 
        "createdAt"=> "2023-05-28", 
        "duration" => "2 mois", 
        "description" => "plateforme de recherche d'emploi pour Externatic", 
        "skills" => ["Figma", "HTML", "CSS", "JavaScript", "Twig", "Bootstrap", "PHP", "Symfony"], 
        "mockup" => "https://www.figma.com/file/PMIJifuRg4Muow4QVBvTkW/P3---Externatic?type=design&node-id=111-542&mode=design&t=7TIsqWefKqFEsejA-0",
        "commits" => 444,
        "github" => "https://github.com/WildCodeSchool/2023-02-php-paris-p3-externatic", 
        "picture" => "externatic.png",
        "video" => "https://www.loom.com/embed/b2266e12c2aa40419b5a8efb44e4042d?sid=155a2e78-bab7-4bbd-8fc5-711732adbe67?hide_owner=true&hide_share=true&hide_title=true&hideEmbedTopBar=true.",
        "link" => "",
    ],
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max:50)]
    private ?string $Title = null;

    #[ORM\Column(length: 1000)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max:1000)]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max:20)]
    private ?string $duration = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 2, max:255)]
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
    #[Assert\NotBlank]
    private Collection $skills;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $commits = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mockup = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $githubLanguages = null;

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

    public function setGithub(?string $github): static
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

    public function setVideo(?string $video): static
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

    public function getCommits(): ?int
    {
        return $this->commits;
    }

    public function setCommits(?int $commits): static
    {
        $this->commits = $commits;

        return $this;
    }

    public function getMockup(): ?string
    {
        return $this->mockup;
    }

    public function setMockup(?string $mockup): static
    {
        $this->mockup = $mockup;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getGithubLanguages(): ?array
    {
        return $this->githubLanguages;
    }

    public function setGithubLanguages(?array $githubLanguages): static
    {
        $this->githubLanguages = $githubLanguages;

        return $this;
    }

}
