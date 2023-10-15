<?php

namespace App\Factories;

use App\Contracts\TemplateParserInterface;
use App\Parsers\TemplateA;
use App\Parsers\TemplateB;
use Illuminate\Support\Str;

class TemplateParserFactory
{
    const CLASSPATH = 'App\\Parsers\\';

    public static function createParser(string $template, array $lines): TemplateParserInterface
    {
        // Convert the template name to a class name format (e.g., "direct_energy" to "DirectEnergy")
        $className = self::getClassName($template);

        if (class_exists($className) && is_subclass_of($className, TemplateParserInterface::class)) {
            return new $className($lines);
        }

        throw new \InvalidArgumentException("Invalid template: $template");
    }

    private static function getClassName($template)
    {
        $path = self::CLASSPATH;

        $className = Str::studly($template);

        return $path . $className . 'Template';
    }
}
