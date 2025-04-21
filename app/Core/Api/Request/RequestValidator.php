<?php

declare(strict_types=1);

namespace App\Core\Api\Request;

use Nette\Http\Request;

interface RequestValidator
{
    public function validate(Request $request): void;
}
