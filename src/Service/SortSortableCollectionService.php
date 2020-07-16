<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;

class SortSortableCollectionService
{
    public function updatePositions(SortableInterface $entity, Collection $collection, int $newPosition): void
    {
        if ($entity->getPosition() === $newPosition) {
            return;
        }

        if ($newPosition < 1) {
            $newPosition = 1;
        } elseif ($newPosition > $collection->count()) {
            $newPosition = $collection->count();
        }

        $position = 0;
        /** @var SortableInterface $entityToUpdate */
        foreach ($collection as $entityToUpdate) {
            $position++;
            if ($position === $newPosition) {
                if ($entity->getPosition() !== $newPosition) {
                    $entity->setPosition($newPosition);
                }
                $position++;
            }
            if ($entity->getId() === $entityToUpdate->getId()) {
                if ($entity->getPosition() === $newPosition) {
                    $position--;
                    continue;
                }
                $entity->setPosition($newPosition);
                $position--;

                continue;
            }

            $entityToUpdate->setPosition($position);
        }
    }
}
