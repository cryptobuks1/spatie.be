<?php

namespace App\Http\Controllers;

class InvoicesController
{
    public function __invoke()
    {
        $transactions = auth()->user()->receipts;

        return view('front.profile.invoices', compact('transactions'));
    }
}
