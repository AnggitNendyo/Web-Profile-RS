<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\Doctor;
use App\Models\CenterOfExcellence;
use App\Models\Article;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\CenterOfExcellenceResource;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Cache;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Cache::remember('site_settings_array', 3600, function () {
            $setting = SiteSetting::first();
            return $setting ? $setting->toArray() : [];
        });

        $stats = [
            'years' => $settings['stats_years'] ?? 15,
            'doctors' => Doctor::count(),
            'patients' => $settings['stats_patients'] ?? 50000,
        ];

        $featuredDoctors = Doctor::with(['specialties', 'schedules'])->where('is_active', true)->inRandomOrder()->take(4)->get();
        $coes = CenterOfExcellence::take(4)->get();
        $latestArticles = Article::with(['author', 'category'])->published()->latest('published_at')->take(3)->get();

        return Inertia::render('Public/Home', [
            'featuredDoctors' => DoctorResource::collection($featuredDoctors),
            'centersOfExcellence' => CenterOfExcellenceResource::collection($coes),
            'latestArticles' => ArticleResource::collection($latestArticles),
            'stats' => $stats,
            'hero' => [
                'title' => $settings['hero_title'] ?? 'Pelayanan Medis Berstandar Internasional',
                'subtitle' => $settings['hero_subtitle'] ?? 'Kesembuhan Anda adalah prioritas utama kami.'
            ]
        ]);
    }
}
