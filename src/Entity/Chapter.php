<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChapterRepository;
use App\Service\SortableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={
 *       "get"={
 *         "security" = "is_granted('CHAPTER_VIEW', object)"
 *       },
 *       "patch"={
 *         "security" = "is_granted('CHAPTER_EDIT', object)",
 *         "input_formats"={"json"={"application/merge-patch+json"}}
 *       }
 *     },
 *     normalizationContext={"groups"={"chapter:read"}},
 *     denormalizationContext={"groups"={"chapter:write"}}
 * )
 * @ORM\Entity(repositoryClass=ChapterRepository::class)
 */
class Chapter implements SortableInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"chapter:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"chapter:write", "chapter:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"chapter:write", "chapter:read"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="chapters")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"chapter:read"})
     */
    private $project;

    /**
     * @ORM\OneToMany(targetEntity=Scene::class, mappedBy="chapter", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "asc"})
     */
    private $scenes;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"chapter:read"})
     */
    private $position;

    public function __construct()
    {
        $this->scenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

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
            $scene->setChapter($this);
        }

        return $this;
    }

    public function removeScene(Scene $scene): self
    {
        if ($this->scenes->contains($scene)) {
            $this->scenes->removeElement($scene);
            // set the owning side to null (unless already changed)
            if ($scene->getChapter() === $this) {
                $scene->setChapter(null);
            }
        }

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
