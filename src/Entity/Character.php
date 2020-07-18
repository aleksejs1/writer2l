<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @ORM\Table(name="`character`")
 */
class Character
{
    public const ROLE_MAJOR = 1;
    public const ROLE_MINOR = 2;
    public const ROLE_LABELS = [
        self::ROLE_MAJOR => 'major',
        self::ROLE_MINOR => 'minor',
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shortName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alternates;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $role;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $goals;

    /**
     * @ORM\ManyToMany(targetEntity=Scene::class, inversedBy="characters")
     */
    private $scenes;

    /**
     * @ORM\OneToMany(targetEntity=Scene::class, mappedBy="viewpoint")
     */
    private $viewpointScenes;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="characters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    public function __construct()
    {
        $this->scenes = new ArrayCollection();
        $this->viewpointScenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getAlternates(): ?string
    {
        return $this->alternates;
    }

    public function setAlternates(?string $alternates): self
    {
        $this->alternates = $alternates;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(?int $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getGoals(): ?string
    {
        return $this->goals;
    }

    public function setGoals(?string $goals): self
    {
        $this->goals = $goals;

        return $this;
    }

    /**
     * @return Collection|Scene[]
     */
    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function addScene(Scene $scene): self
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes[] = $scene;
        }

        return $this;
    }

    public function removeScene(Scene $scene): self
    {
        if ($this->scenes->contains($scene)) {
            $this->scenes->removeElement($scene);
        }

        return $this;
    }

    /**
     * @return Collection|Scene[]
     */
    public function getViewpointScenes(): Collection
    {
        return $this->viewpointScenes;
    }

    public function addViewpointScene(Scene $viewpointScene): self
    {
        if (!$this->viewpointScenes->contains($viewpointScene)) {
            $this->viewpointScenes[] = $viewpointScene;
            $viewpointScene->setViewpoint($this);
        }

        return $this;
    }

    public function removeViewpointScene(Scene $viewpointScene): self
    {
        if ($this->viewpointScenes->contains($viewpointScene)) {
            $this->viewpointScenes->removeElement($viewpointScene);
            // set the owning side to null (unless already changed)
            if ($viewpointScene->getViewpoint() === $this) {
                $viewpointScene->setViewpoint(null);
            }
        }

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
