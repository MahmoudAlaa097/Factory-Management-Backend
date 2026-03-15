<?php
namespace App\Enums;

enum ComponentCategory: string
{
    case Electrical = 'electrical';
    case Mechanical = 'mechanical';

    public function is(self $category): bool
    {
        return $this === $category;
    }
}
