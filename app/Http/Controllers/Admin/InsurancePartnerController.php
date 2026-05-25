<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsurancePartner;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class InsurancePartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InsurancePartner::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('type', 'like', '%' . $request->search . '%');
        }

        $partners = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/InsurancePartners/Index', [
            'partners' => $partners,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/InsurancePartners/Form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $validated;
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('insurances', 'public');
        }

        InsurancePartner::create($data);

        return redirect()->route('admin.insurance-partners.index')
            ->with('message', 'Mitra asuransi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InsurancePartner $insurancePartner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InsurancePartner $insurancePartner)
    {
        return Inertia::render('Admin/InsurancePartners/Form', [
            'partner' => $insurancePartner,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InsurancePartner $insurancePartner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $validated;
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            if ($insurancePartner->logo_path) {
                Storage::disk('public')->delete($insurancePartner->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('insurances', 'public');
        }

        $insurancePartner->update($data);

        return redirect()->route('admin.insurance-partners.index')
            ->with('message', 'Mitra asuransi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InsurancePartner $insurancePartner)
    {
        if ($insurancePartner->logo_path) {
            Storage::disk('public')->delete($insurancePartner->logo_path);
        }
        
        $insurancePartner->delete();

        return redirect()->route('admin.insurance-partners.index')
            ->with('message', 'Mitra asuransi berhasil dihapus.');
    }
}
