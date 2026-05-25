<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\JobVacancy;
use Inertia\Inertia;

class JobVacancyController extends Controller
{
    public function index()
    {
        $vacancies = JobVacancy::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('deadline')
                      ->orWhere('deadline', '>=', now()->toDateString());
            })
            ->latest()
            ->get();

        return Inertia::render('Careers/Index', [
            'vacancies' => $vacancies,
        ]);
    }
}
