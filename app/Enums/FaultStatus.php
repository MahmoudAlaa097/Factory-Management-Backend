<?php

namespace App\Enums;

enum FaultStatus: string
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case OperatorAccepted = 'operator_accepted';
    case MaintenanceApproved = 'maintenance_approved';
    case Closed = 'closed';

    public function is(self $status): bool
    {
        return $this === $status;
    }
}
