<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JobVacancy::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('department', 'like', '%' . $request->search . '%');
        }

        $vacancies = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/JobVacancies/Index', [
            'vacancies' => $vacancies,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/JobVacancies/Form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'apply_link' => 'nullable|url|max:255',
            'apply_email' => 'nullable|email|max:255',
            'deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $data = $validated;
        $data['slug'] = Str::slug($data['title']) . '-' . rand(100, 999);
        $data['is_active'] = $request->boolean('is_active', true);

        JobVacancy::create($data);

        return redirect()->route('admin.job-vacancies.index')
            ->with('message', 'Lowongan kerja berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobVacancy $jobVacancy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobVacancy $jobVacancy)
    {
        return Inertia::render('Admin/JobVacancies/Form', [
            'vacancy' => $jobVacancy,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobVacancy $jobVacancy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'apply_link' => 'nullable|url|max:255',
            'apply_email' => 'nullable|email|max:255',
            'deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $data = $validated;
        
        if ($jobVacancy->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']) . '-' . rand(100, 999);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $jobVacancy->update($data);

        return redirect()->route('admin.job-vacancies.index')
            ->with('message', 'Lowongan kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobVacancy $jobVacancy)
    {
        $jobVacancy->delete();

        return redirect()->route('admin.job-vacancies.index')
            ->with('message', 'Lowongan kerja berhasil dihapus.');
    }
}
