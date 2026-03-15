<?php

namespace App\Enums;

enum ManagementType: string
{
    case Production = 'production';
    case ElectricalMaintenance = 'electrical_maintenance';
    case MechanicalMaintenance = 'mechanical_maintenance';


    public function is(self $type): bool
    {
        return $this === $type;
    }

    public function isProduction(): bool
    {
        return $this === self::Production;
    }

    public function isElectricalMaintenance(): bool
    {
        return $this === self::ElectricalMaintenance;
    }

    public function isMechanicalMaintenance(): bool
    {
        return $this === self::MechanicalMaintenance;
    }

    public function isMaintenance(): bool
    {
        return $this === self::ElectricalMaintenance || $this === self::MechanicalMaintenance;
    }
}
