<?php

namespace App\Service;

use App\Entity\Character;

class CharacterMetadata
{
    public function get(Character $character)
    {
        $words = 0;
        foreach ($character->getViewpointScenes() as $scene) {
            if (!$scene->getContent()) {
                continue;
            }
            $words += count(explode(' ', $scene->getContent()));
        }
        return [
            'vpScenes' => $character->getViewpointScenes()->count(),
            'scenes' => $character->getScenes()->count(),
            'words' => $words,
        ];
    }
}
