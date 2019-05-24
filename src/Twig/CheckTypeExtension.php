<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CheckTypeExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_class', [$this, 'getClass']),
            new TwigFunction('is_array', [$this, 'isArray']),
            new TwigFunction('is_bool', [$this, 'isBool']),
            new TwigFunction('is_string', [$this, 'isString']),
        ];
    }

    public function getClass($value)
    {
        return get_class($value);
    }

    public function isArray($value)
    {
        return is_array($value);
    }

    public function isBool($value)
    {
        return is_bool($value);
    }

    public function isString($value)
    {
        return is_string($value);
    }
}
