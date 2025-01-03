<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Developer;
use App\Entity\JobPosting;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(targetEntity: Developer::class, inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Developer $developer = null;

    #[ORM\ManyToOne(targetEntity: JobPosting::class, inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JobPosting $jobPosting = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $availabilityDate = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $motivation = null;

    #[ORM\ManyToOne(targetEntity: Status::class, inversedBy: 'candidats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeveloper(): ?Developer
    {
        return $this->developer;
    }

    public function setDeveloper(?Developer $developer): self
    {
        $this->developer = $developer;
        return $this;
    }

    public function getJobPosting(): ?JobPosting
    {
        return $this->jobPosting;
    }

    public function setJobPosting(?JobPosting $jobPosting): self
    {
        $this->jobPosting = $jobPosting;
        return $this;
    }

    public function getAvailabilityDate(): ?\DateTimeInterface
    {
        return $this->availabilityDate;
    }

    public function setAvailabilityDate(\DateTimeInterface $availabilityDate): self
    {
        $this->availabilityDate = $availabilityDate;
        return $this;
    }

    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(?string $motivation): self
    {
        $this->motivation = $motivation;
        return $this;
    }


    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;
        return $this;
    }
    
}
