<?php

namespace App\Entity;

use App\Repository\SceneRevisionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SceneRevisionRepository::class)
 */
class SceneRevision
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $version;

    /**
     * @ORM\ManyToOne(targetEntity=Scene::class, inversedBy="sceneRevisions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scene;

    public function __construct()
    {
        $this->version = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVersion(): ?\DateTimeInterface
    {
        return $this->version;
    }

    public function getScene(): ?Scene
    {
        return $this->scene;
    }

    public function setScene(?Scene $scene): self
    {
        $this->scene = $scene;

        return $this;
    }
}
