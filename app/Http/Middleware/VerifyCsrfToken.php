<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/search',
        'api/user',
        'api/abo/make',
        'api/abo/remove',
        'api/abo/cadence',
        'api/arrets',
        'api/authors',
        'api/categories',
        'api/years',
        'api/analyses',
        'api/newsletter',
        'api/campagne',
        'api/archives',
        'api/homepage',
        'api/menu',
        'api/page',
    ];
}
