<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\InsurancePartner;
use Inertia\Inertia;

class InsurancePartnerController extends Controller
{
    public function index()
    {
        $partners = InsurancePartner::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('InsurancePartners/Index', [
            'partners' => $partners,
        ]);
    }
}
