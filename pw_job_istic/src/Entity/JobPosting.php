<?php

namespace App\Entity;

use App\Repository\JobPostingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Candidat;

#[ORM\Entity(repositoryClass: JobPostingRepository::class)]
class JobPosting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;


    #[ORM\Column(length: 3000, nullable: true)]
    private ?string $experienceLevel = null;

    #[ORM\Column]
    private ?int $salary = null;

    #[ORM\Column(length: 3000, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $pdfFile = null;

    #[ORM\ManyToOne(targetEntity: JobType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private JobType $jobType;

    #[ORM\ManyToOne(targetEntity: Society::class, inversedBy: 'jobPostings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Society $society = null;


    #[ORM\ManyToMany(targetEntity: Langage::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: 'jobposting_technologies')]
    private Collection $technologies;


    #[ORM\OneToMany(mappedBy: 'jobPosting', targetEntity: Candidat::class, cascade: ['remove'])]
    private Collection $candidatures;

    public function __construct()
    {
        $this->technologies = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function addTechnology(Langage $technology): self
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies[] = $technology;
        }
    
        return $this;
    }

    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }
    
    public function removeTechnology(Langage $technology): self
    {
        $this->technologies->removeElement($technology);
    
        return $this;
    }

    public function getExperienceLevel(): ?string
    {
        return $this->experienceLevel;
    }

    public function setExperienceLevel(string $experienceLevel): static
    {
        $this->experienceLevel = $experienceLevel;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPdfFile(): ?string
    {
        return $this->pdfFile;
    }

    public function setPdfFile(?string $pdfFile): self
    {
        $this->pdfFile = $pdfFile;

        return $this;
    }

    public function getJobType(): JobType
    {
        return $this->jobType;
    }

    public function setJobType(JobType $jobType): self
    {
        $this->jobType = $jobType;

        return $this;
    }

    public function getSociety(): ?Society
    {
        return $this->society;
    }

    public function setSociety(?Society $society): self
    {
        $this->society = $society;

        return $this;
    }


    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }
}
