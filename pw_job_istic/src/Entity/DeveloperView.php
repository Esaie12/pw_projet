<?php

namespace App\Entity;

use App\Repository\DeveloperViewRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Society;
use App\Entity\Developer;

#[ORM\Entity(repositoryClass: DeveloperViewRepository::class)]
class DeveloperView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Developer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Developer $developer = null;

    #[ORM\ManyToOne(targetEntity: Society::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Society $society = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $viewedAt = null;

    public function __construct()
    {
        $this->viewedAt = new \DateTimeImmutable();
    }

    // Getters and setters...
    public function getId(): ?int { return $this->id; }
    public function getDeveloper(): ?Developer { return $this->developer; }

    public function setDeveloper(?Developer $developer): self 
    { $this->developer = $developer; return $this; 
    }

    public function getSociety(): ?Society { return $this->society; }
    public function setSociety(?Society $society): self { $this->society = $society; return $this; }
    public function getViewedAt(): ?\DateTimeInterface { return $this->viewedAt; }
}
