<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class SerieRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function displayToBadge(string $string)
    {
        $tableau = explode(' / ', $string);
        foreach ($tableau as &$item) {
            $item = '<span class="badge">'.$item.'</span>';
        }
        return implode('', $tableau);
    }
}
