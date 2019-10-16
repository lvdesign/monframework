<?php

namespace Framework\Twig;

use Twig\TwigFilter;

/**
 *  Serie extension pour texte
 * @package Framework\Twig
 */
class TextExtension extends \Twig\Extension\AbstractExtension
{

    /**
     *
     * @return Twig\TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('excerpt', [$this, 'excerpt'])
        ];
    }

    /**
     * excerpt renvoie extrait de text selon maxLength excerpt
     * mb_strpos — Find position of first occurrence of string in a string
     * mb_strrpos — Find position of last occurrence of a string in a string
     *
     * @param  string $contenu
     * @param  int $maxLength
     *
     * @return string
     */
    public function excerpt(string $content, int $maxLength = 100): string
    {
        if (mb_strlen($content) >  $maxLength) {
            $excerpt  = mb_substr($content, 0, $maxLength);

            $lastSpace = mb_strrpos($excerpt, ' ');
            return  mb_substr($excerpt, 0, $lastSpace) . '...' ;
        }
        return $content;
    }
}
