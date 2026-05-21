<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\LkmSetting;
use App\Models\LkmSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LKMGraftingController extends Controller
{
    public function index()
    {

        $mahasiswa = auth()->user()->mahasiswa;

        // Ambil semua setting LKM yang ada
        $settings = LkmSetting::get()->keyBy('pertemuan');

        // riwayat submission mahasiswa
        $submissions = LkmSubmission::where('mahasiswa_id', $mahasiswa->id)
            ->get()
            ->keyBy('pertemuan');

        // gabung data untuk dikirim ke vue
        $listPertemuan = collect([1, 2, 3, 4])->map(function ($p) use ($settings, $submissions) {
            $setting = $settings->get($p);
            $submission = $submissions->get($p);

            // Tentukan status akses waktu
            $now = Carbon::now();
            $isOpen = $setting && $now->between($setting->open_at, $setting->deadline_at);
            $isOverdue = $setting && $now->greaterThan($setting->deadline_at);

            return [
                'pertemuan' => $p,
                'judul' => "LKM Pertemuan $p",
                'setting' => $setting,
                'submission' => $submission,
                'status_akses' => [
                    'is_open' => $isOpen,
                    'is_overdue' => $isOverdue,
                    'bisa_mengisi' => $isOpen || ($isOverdue && $setting?->allow_late_submit === 'Ya'),
                ],
            ];
        });

        return Inertia::render('RoleMahasiswa/Pembelajaran/LKMGrafting/Index', [
            'listPertemuan' => $listPertemuan,
        ]);
    }

    public function showForm($pertemuan)
    {
        $pertemuan = (int) $pertemuan;
        $mahasiswa = Auth::user()->mahasiswa;

        // Ambil atau buat data submission (First or Create)
        $submission = LkmSubmission::firstOrCreate([
            'mahasiswa_id' => $mahasiswa->id,
            'pertemuan' => $pertemuan,
        ], [
            'lkm_setting_id' => LkmSetting::where('pertemuan', $pertemuan)->first()?->id ?? 1,
            'status' => 'draft',
        ]);

        // Eager load data spesifik sesuai nomor pertemuan untuk di-edit
        $submission->load([
            'lkmSetting',
            'p1Observations', 'p1Questions',
            'p2Specs', 'p2Items', 'p2Steps',
            'p3Monitorings',
            'p4Finals', 'p4Reflections',
        ]);

        // Arahkan ke file Vue yang berbeda secara dinamis berdasarkan nomor pertemuan
        return Inertia::render('RoleMahasiswa/Pembelajaran/LKMGrafting/Form', [
            'submission' => $submission,
        ]);
    }

    public function storeData(Request $request, $pertemuan)
    {

        $mahasiswa = Auth::user()->mahasiswa;

        $submission = LkmSubmission::where('mahasiswa_id', $mahasiswa->id)
            ->where('pertemuan', $pertemuan)
            ->firstOrFail();

        // Cek jika submit gausa edit lagi
        if ($submission->status === 'submitted') {
            return redirect()->back()->with('error', 'LKM ini sudah dikunci.');
        }

        // LKM 1
        if ($pertemuan == 1) {
            // Essay
            $submission->p1Questions()->updateOrCreate(
                ['lkm_submission_id' => $submission->id],
                $request->questions
            );

            // Tabel Pengamatan
            foreach ($request->observations as $obs) {
                $submission->p1Observations()->updateOrCreate(
                    [
                        'lkm_submission_id' => $submission->id,
                        'nama_tanaman' => $obs['nama_tanaman'],
                        'organ' => $obs['organ'],
                    ],
                    [
                        'morfologis' => $obs['morfologis'],
                        'anatomis' => $obs['anatomis'],
                    ]
                );
            }
        }

        // LKM 2
        if ($pertemuan == 2) {
            // p2items
            $submission->p2Items()->delete();
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $item) {
                    if (! empty($item['alat'])) {
                        $submission->p2Items()->create([
                            'nama_item' => $item['alat'],
                            'jenis' => 'alat',
                        ]);
                    }
                    if (! empty($item['bahan'])) {
                        $submission->p2Items()->create([
                            'nama_item' => $item['bahan'],
                            'jenis' => 'bahan',
                        ]);
                    }
                }
            }

            // Essay
            $submission->p2Specs()->updateOrCreate(
                ['lkm_submission_id' => $submission->id],
                $request->specifications
            );
        }

        // JIKA MAHASISWA KLIK "SUBMIT FINAL"
        if ($request->action === 'submit') {
            $submission->update([
                'status' => 'submitted',
                'submitted_at' => Carbon::now(),
            ]);

            return redirect('/mahasiswa/pembelajaran/lkm-grafting')->with('success', 'LKM Berhasil Dikirim!');
        }

        return redirect()->back()->with('success', 'Draft Berhasil Disimpan.');
    }
}
