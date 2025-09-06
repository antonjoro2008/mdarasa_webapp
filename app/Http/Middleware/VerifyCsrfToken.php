<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/students/c2b/mdarasapaymentengine',
        '/students/c2b/validatepayment',
        '/ilu/c2b/paymentengine',
        '/ilu/c2b/validatepayment',
        '/ilu/deposit/stk',
        '/revenue/c2b/paymentengine',
        '/revenue/c2b/validatepayment',
        '/revenue/deposit/stk',
        '/revenue/ussd',
    ];
}