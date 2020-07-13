<?php

namespace App\Entity;

use App\Repository\SceneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SceneRepository::class)
 */
class Scene
{
    public const STATUS_OUTLINE = 1;
    public const STATUS_DRAFT = 2;
    public const STATUS_1ST_EDITION = 3;
    public const STATUS_2ND_EDITION = 4;
    public const STATUS_DONE = 5;
    public const STATUS_TITLES = [
        self::STATUS_OUTLINE => 'Outline',
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_1ST_EDITION => '1st edition',
        self::STATUS_2ND_EDITION => '2nd edition',
        self::STATUS_DONE => 'Done',
    ];

    public const GOAL_ACTION = 1;
    public const GOAL_REACTION = 2;
    public const GOAL_TITLES = [
        self::GOAL_ACTION => 'Action',
        self::GOAL_REACTION => 'Reaction',
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Chapter::class, inversedBy="scenes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chapter;

    /**
     * @ORM\ManyToMany(targetEntity=Character::class, mappedBy="scenes")
     */
    private $characters;

    /**
     * @ORM\ManyToOne(targetEntity=Character::class, inversedBy="viewpointScenes")
     */
    private $viewpoint;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="integer")
     */
    private $goalType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $goal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $conflict;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $outcome;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->addScene($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->contains($character)) {
            $this->characters->removeElement($character);
            $character->removeScene($this);
        }

        return $this;
    }

    public function getViewpoint(): ?Character
    {
        return $this->viewpoint;
    }

    public function setViewpoint(?Character $viewpoint): self
    {
        $this->viewpoint = $viewpoint;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getGoalType(): ?int
    {
        return $this->goalType;
    }

    public function setGoalType(int $goalType): self
    {
        $this->goalType = $goalType;

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(?string $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getConflict(): ?string
    {
        return $this->conflict;
    }

    public function setConflict(?string $conflict): self
    {
        $this->conflict = $conflict;

        return $this;
    }

    public function getOutcome(): ?string
    {
        return $this->outcome;
    }

    public function setOutcome(?string $outcome): self
    {
        $this->outcome = $outcome;

        return $this;
    }
}
