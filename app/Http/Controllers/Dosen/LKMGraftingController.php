<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\LkmSetting;
use App\Models\LkmSubmission;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LKMGraftingController extends Controller
{
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

        return Inertia::render('RoleDosen/Pembelajaran/LKMGrafting/Submissions', [
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
                'catatan_dosen' => $submission ? $submission->catatan_dosen : null,
            ];
        });

        return Inertia::render('RoleDosen/Pembelajaran/LKMGrafting/ShowMahasiswa', [
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

    public function updateCatatan(Request $request, $id)
    {
        $submission = LkmSubmission::findOrFail($id);

        $request->validate([
            'catatan_dosen' => 'nullable|string',
        ]);

        $submission->update([
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return back()->with('success', 'Catatan berhasil diperbarui!');
    }
}
