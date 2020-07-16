<?php

namespace App\Service;

interface SortableInterface
{
    public function getId(): ?int;

    public function getPosition(): ?int;

    public function setPosition(int $position): self;
}
