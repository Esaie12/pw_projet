<?php

namespace App\Entity;

use App\Repository\DeveloperRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Langage;
use App\Entity\User;
use App\Entity\Candidat;

#[ORM\Entity(repositoryClass: DeveloperRepository::class)]
class Developer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(nullable: true)]
    private ?string $aboutMe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisation = null;

    #[ORM\Column(nullable: true)]
    private ?int $salary = null;

    #[ORM\Column(nullable: true)]
    private ?int $niveau_experience = null;

    #[ORM\OneToOne(inversedBy: 'developer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\ManyToMany(targetEntity: Langage::class)]
    #[ORM\JoinTable(name: 'developer_langages')]
    private Collection $langages;

    #[ORM\ManyToMany(targetEntity: JobPosting::class)]
    #[ORM\JoinTable(name: 'developer_favorites')]
    private Collection $favoriteJobs;

    #[ORM\OneToMany(mappedBy: 'developer', targetEntity: Candidat::class, cascade: ['remove'])]
    private Collection $candidatures;

    #[ORM\OneToMany(mappedBy: 'developer', targetEntity: DeveloperView::class, cascade: ['remove'])]
    private Collection $views;

    public function __construct()
    {
        $this->langages = new ArrayCollection();
        $this->favoriteJobs = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->views = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
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

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(?int $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getNiveauExperience(): ?int
    {
        return $this->niveau_experience;
    }

    public function setNiveauExperience(?int $niveau_experience): static
    {
        $this->niveau_experience = $niveau_experience;

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

    public function getAboutMe(): ?string
    {
        return $this->aboutMe;
    }

    public function setAboutMe(?string $aboutMe): self
    {
        $this->aboutMe = $aboutMe;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLangages(): Collection
    {
        return $this->langages;
    }

    public function addLangage(Langage $langage): self
    {
        if (!$this->langages->contains($langage)) {
            $this->langages->add($langage);
        }

        return $this;
    }

    public function removeLangage(Langage $langage): self
    {
        $this->langages->removeElement($langage);

        return $this;
    }


    public function getFavoriteJobs(): Collection
    {
        return $this->favoriteJobs;
    }

    public function addFavoriteJob(JobPosting $jobPosting): self
    {
        if (!$this->favoriteJobs->contains($jobPosting)) {
            $this->favoriteJobs->add($jobPosting);
        }

        return $this;
    }

    public function removeFavoriteJob(JobPosting $jobPosting): self
    {
        $this->favoriteJobs->removeElement($jobPosting);

        return $this;
    }

    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function getViews(): Collection
    {
        return $this->views;
    }

}
