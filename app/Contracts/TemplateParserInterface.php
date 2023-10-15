<?php

namespace app\Contracts;

interface TemplateParserInterface
{
    public function parse(array $lines);
}
