<?php

namespace App\Contracts;

interface LogInterface
{
    public function formatted(): array;

    public function columns(): array;
}
