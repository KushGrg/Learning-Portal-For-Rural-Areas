<?php

namespace App\Traits;

use BenSampo\Enum\Enum;

trait HasStatus
{
    /**
     * Convert a BenSampo Enum to a select-friendly array.
     *
     * @param  class-string<Enum>  $enumClass
     */
    public function status(string $enumClass, string $valueField = 'id', string $labelField = 'name'): array
    {
        return collect($enumClass::asSelectArray())
            ->map(fn ($label, $value) => [
                $valueField => $value,
                $labelField => $label,
            ])
            ->values()
            ->toArray();
    }
}
