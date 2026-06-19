<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LkmSetting;
use App\Models\LkmSubmission;
use App\Models\Mahasiswa;
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
            'pertemuan' => 'integer|required|unique:lkm_settings,pertemuan,'.$lkm->id,
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

    // public function destroySetting(string $id)
    // {
    //     $lkm = LkmSetting::findOrFail($id);

    //     $lkm->delete();

    //     return redirect('/admin/pembelajaran/lkm-grafting/settings')->with('success', 'Data LKM berhasil dihapus!');
    // }

    public function indexSubmission(Request $request)
    {
        $search = $request->input('search');

        $mahasiswas = Mahasiswa::with('user')
            ->whereHas('submissions')
            ->when($search, function ($query, $search) {
                $query->where('nim', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });
            })
            ->simplePaginate(10)
            ->withQueryString();

        return Inertia::render('RoleAdmin/Pembelajaran/LKMGrafting/Tabs/LKMSubmissions/Index', [
            'mahasiswas' => $mahasiswas,
            'title' => 'LKM Submissions',
            'filters' => ['search' => $search],
        ]);
    }

    public function showMahasiswaSubmissions($mahasiswaId)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($mahasiswaId);
        $settings = LkmSetting::orderBy('pertemuan', 'asc')->get();

        $submissions = LkmSubmission::where('mahasiswa_id', $mahasiswaId)
            ->get()
            ->keyBy('pertemuan');

        $lkmData = $settings->map(function ($setting) use ($submissions) {
            $submission = $submissions->get($setting->pertemuan);

            return [
                'pertemuan' => $setting->pertemuan,
                'title' => $setting->title,
                'status' => $submission ? $submission->status : 'Belum Mengerjakan',
                'submission_id' => $submission ? $submission->id : null,
            ];
        });

        return Inertia::render('RoleAdmin/Pembelajaran/LKMGrafting/Tabs/LKMSubmissions/ShowMahasiswa', [
            'mahasiswa' => $mahasiswa,
            'lkmData' => $lkmData,
        ]);
    }

    public function showSubmission($id)
    {
        $submission = LkmSubmission::with([
            'lkmSetting', 'mahasiswa.user',
            // P1
            'p1Questions', 'p1Specs', 'p1Items', 'p1Procedures', 'p1Schedules',
            // P2
            'p2Items', 'p2Specs', 'p2Procedures', 'p2Monitorings', 'p2Questions',
            // P3
            'p3Growths', 'p3Scions', 'p3Rootstocks', 'p3Connections', 'p3Questions',
            // P4
            'p4Analyses', 'p4DeepQuestions', 'p4SelfAssessments', 'p4Reflections',
        ])->findOrFail($id);

        return Inertia::render('RoleMahasiswa/Pembelajaran/LKMGrafting/Form', [
            'submission' => $submission,
            'isAdmin' => true,
        ]);
    }
}
