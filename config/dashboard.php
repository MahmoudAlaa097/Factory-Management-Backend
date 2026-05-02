<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Machine Status Thresholds (in hours)
    |--------------------------------------------------------------------------
    |
    | Score = sum of hours each active fault has been open.
    | healthy  → 0 active faults
    | faulty   → score <= faulty threshold
    | at_risk  → faulty < score <= at_risk threshold
    | critical → score > at_risk threshold
    |
    */
    'machine_status' => [
        'faulty'  => 2,
        'at_risk' => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Activity Feed
    |--------------------------------------------------------------------------
    */
    'activity_days'     => 7,
    'activity_per_page' => 15,
];
