<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Doctor;
use App\Models\Article;
use App\Models\Facility;
use App\Models\CenterOfExcellence;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'doctors' => Doctor::count(),
                'articles' => Article::count(),
                'facilities' => Facility::count(),
                'coes' => CenterOfExcellence::count(),
            ]
        ]);
    }
}
