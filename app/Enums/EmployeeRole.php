<?php

namespace App\Enums;

enum EmployeeRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Engineer = 'engineer';
    case Supervisor = 'supervisor';
    case Technician = 'technician';
    case Operator = 'operator';

    public function is(self $role): bool
    {
        return $this === $role;
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }
}
