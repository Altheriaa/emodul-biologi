<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LkmSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LKMGraftingController extends Controller
{
    public function index()
    {
        return redirect('/admin/pembelajaran/lkm-grafting/settings');
    }

    public function indexSetting(Request $request)
    {
        $search = $request->input('search');
        $lkms = LkmSetting::query()
            ->with('createdBy')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('pertemuan', 'like', "%{$search}%");
                });
            })
            ->orderBy('pertemuan', 'desc')
            ->simplePaginate(10)
            ->withQueryString();

        return Inertia::render('RoleAdmin/Pembelajaran/LKMGrafting/Tabs/LKMSettings/Index', [
            'lkms' => $lkms,
            'title' => 'LKM Settings',
            'filters' => ['search' => $search],
        ]);
    }

    public function createSetting()
    {
        return Inertia::render('RoleAdmin/Pembelajaran/LKMGrafting/Tabs/LKMSettings/Create');
    }

    public function storeSetting(Request $request)
    {

        $request->validate([
            'pertemuan' => 'integer|required|unique:lkm_settings,pertemuan',
            'title' => 'string|required',
            'deskripsi' => 'string|required',
            'open_at' => 'date|required',
            'deadline_at' => 'date|required',
            'is_active' => 'boolean|required',
            'allow_late_submit' => 'boolean|required',
        ]);

        LkmSetting::create([
            'pertemuan' => $request->pertemuan,
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'open_at' => $request->open_at,
            'deadline_at' => $request->deadline_at,
            'is_active' => $request->is_active,
            'allow_late_submit' => $request->allow_late_submit,
            'created_by' => Auth()->user()->id,
        ]);

        return redirect('/admin/pembelajaran/lkm-grafting/settings')->with('success', 'Data LKM berhasil ditambahkan!');

    }

    public function editSetting(string $id)
    {
        $lkm = LkmSetting::findOrFail($id);

        return Inertia::render('RoleAdmin/Pembelajaran/LKMGrafting/Tabs/LKMSettings/Edit', [
            'lkm' => $lkm,
        ]);
    }

    public function updateSetting(Request $request, string $id)
    {
        $lkm = LkmSetting::findOrFail($id);

        $request->validate([
            'pertemuan' => 'integer|required|unique:lkm_settings,pertemuan,'. $lkm->id,
            'title' => 'string|required',
            'deskripsi' => 'string|required',
            'open_at' => 'date|required',
            'deadline_at' => 'date|required',
            'is_active' => 'boolean|required',
            'allow_late_submit' => 'boolean|required',
        ]);

        $lkm->update([
            'pertemuan' => $request->pertemuan,
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'open_at' => $request->open_at,
            'deadline_at' => $request->deadline_at,
            'is_active' => $request->is_active,
            'allow_late_submit' => $request->allow_late_submit,
            'created_by' => Auth()->user()->id,
        ]);

        return redirect('/admin/pembelajaran/lkm-grafting/settings')->with('success', 'Data LKM berhasil ditambahkan!');

    }

    public function destroySetting(string $id)
    {
        $lkm = LkmSetting::findOrFail($id);

        $lkm->delete();

        return redirect('/admin/pembelajaran/lkm-grafting/settings')->with('success', 'Data LKM berhasil dihapus!');
    }

    public function indexSubmission()
    {
        return Inertia::render('RoleAdmin/Pembelajaran/LKMGrafting/Tabs/LKMSubmission');
    }
}
