<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Visit deduplication window
    |--------------------------------------------------------------------------
    |
    | Same visitor + same path will only create one row within this many
    | minutes (repeated refreshes do not inflate counts). After the window
    | elapses, another view of the same path is logged again.
    |
    */
    'dedupe_ttl_minutes' => (int) env('SITE_TRAFFIC_DEDUPE_TTL_MINUTES', 30),

];
