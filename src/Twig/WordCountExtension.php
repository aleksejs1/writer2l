<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class WordCountExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('word_count', [$this, 'wordCount']),
        ];
    }

    public function wordCount(?string $value)
    {
        if (!$value) {
            return 0;
        }

        return str_word_count($value);
    }
}
