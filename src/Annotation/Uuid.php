<?php declare(strict_types=1);

namespace App\Annotation;

class Uuid
{
    const PATTERN = '[0-9a-zAZ]{8}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{12}';
}
