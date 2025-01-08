<?php

namespace App\Entity;

use App\Repository\SocietyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: SocietyRepository::class)]
class Society
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'society', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(nullable: true)]
    private ?array $galleries = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $creation_annee = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $about = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebook = null;


    #[ORM\OneToMany(mappedBy: 'society', targetEntity: JobPosting::class)]
    private Collection $jobPostings;

    #[ORM\ManyToMany(targetEntity: Developer::class)]
    #[ORM\JoinTable(name: 'society_favorites')]
    private Collection $favoriteDevelopers;

    public function __construct()
    {
        $this->favoriteDevelopers = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getGalleries(): ?array
    {
        return $this->galleries;
    }

    public function setGalleries(?array $galleries): static
    {
        $this->galleries = $galleries;

        return $this;
    }


    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }


    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getCreationAnnee(): ?int
    {
        return $this->creation_annee;
    }

    public function setCreationAnnee(?int $creation_annee): static
    {
        $this->creation_annee = $creation_annee;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): static
    {
        $this->about = $about;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): static
    {
        $this->facebook = $facebook;

        return $this;
    }


    public function getJobPostings(): Collection
    {
        return $this->jobPostings;
    }

    public function addJobPosting(JobPosting $jobPosting): self
    {
        if (!$this->jobPostings->contains($jobPosting)) {
            $this->jobPostings[] = $jobPosting;
            $jobPosting->setSociety($this);
        }

        return $this;
    }

    public function removeJobPosting(JobPosting $jobPosting): self
    {
        if ($this->jobPostings->removeElement($jobPosting)) {
            // Set the owning side to null (unless already changed)
            if ($jobPosting->getSociety() === $this) {
                $jobPosting->setSociety(null);
            }
        }

        return $this;
    }

    public function isFavorite(Developer $developer): bool
    {
        return $this->favoriteDevelopers->contains($developer);
    }

    public function getFavoriteDevelopers(): Collection
    {
        return $this->favoriteDevelopers;
    }

    public function addFavoriteDeveloper(Developer $developer): self
    {
        if (!$this->favoriteDevelopers->contains($developer)) {
            $this->favoriteDevelopers->add($developer);
        }
        return $this;
    }

    public function removeFavoriteDeveloper(Developer $developer): self
    {
        $this->favoriteDevelopers->removeElement($developer);
        return $this;
    }


}
