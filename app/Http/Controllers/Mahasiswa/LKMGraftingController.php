<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\LkmSetting;
use App\Models\LkmSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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

        // Eager load data spesifik sesuai nomor pertemuan
        $submission->load([
            'lkmSetting',
            // P1
            'p1Questions', 'p1Specs', 'p1Items', 'p1Procedures', 'p1Schedules',
            // P2
            'p2Items', 'p2Specs', 'p2Procedures', 'p2Monitorings', 'p2Questions',
            // P3
            'p3Growths', 'p3Scions', 'p3Rootstocks', 'p3Connections', 'p3Questions',
            // P4
            'p4Analyses', 'p4DeepQuestions', 'p4SelfAssessments', 'p4Reflections',
        ]);

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

        // =============================================================
        // LKM 1 (Sintak 1-3)
        // =============================================================
        if ($pertemuan == 1) {
            // Sintak 1: Pertanyaan Esensial
            $submission->p1Questions()->updateOrCreate(
                ['lkm_submission_id' => $submission->id],
                $request->questions
            );

            // Sintak 2: Pemilihan Tanaman (Specs)
            $submission->p1Specs()->delete();
            if ($request->has('specs') && is_array($request->specs)) {
                foreach ($request->specs as $spec) {
                    if (! empty($spec['variabel'])) {
                        $submission->p1Specs()->create([
                            'variabel' => $spec['variabel'],
                            'tanaman_a' => $spec['tanaman_a'],
                            'tanaman_b' => $spec['tanaman_b'],
                            'alasan_pemilihan' => $spec['alasan_pemilihan'],
                        ]);
                    }
                }
            }

            // Sintak 2: Alat & Bahan
            $submission->p1Items()->delete();
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $index => $item) {
                    if (! empty($item['alat']) || ! empty($item['bahan'])) {
                        $submission->p1Items()->create([
                            'nomor' => $index + 1,
                            'alat' => $item['alat'],
                            'bahan' => $item['bahan'],
                        ]);
                    }
                }
            }

            // Sintak 3: Prosedur Kerja
            $submission->p1Procedures()->delete();
            if ($request->has('procedures') && is_array($request->procedures)) {
                foreach ($request->procedures as $proc) {
                    if (! empty($proc['tahap']) || ! empty($proc['penjelasan'])) {
                        $submission->p1Procedures()->create([
                            'step_number' => $proc['step_number'],
                            'tahap' => $proc['tahap'],
                            'penjelasan' => $proc['penjelasan'],
                        ]);
                    }
                }
            }

            // Sintak 3: Jadwal Capaian
            $submission->p1Schedules()->delete();
            if ($request->has('schedules') && is_array($request->schedules)) {
                foreach ($request->schedules as $sched) {
                    if (! empty($sched['target_kegiatan'])) {
                        $submission->p1Schedules()->create([
                            'pertemuan_ke' => $sched['pertemuan_ke'],
                            'target_kegiatan' => $sched['target_kegiatan'],
                        ]);
                    }
                }
            }
        }

        // =============================================================
        // LKM 2 (Sintak 4)
        // =============================================================
        if ($pertemuan == 2) {
            // Persiapan Alat & Bahan
            $submission->p2Items()->delete();
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $index => $item) {
                    if (! empty($item['nama_item'])) {
                        $submission->p2Items()->create([
                            'nomor' => $index + 1,
                            'nama_item' => $item['nama_item'],
                        ]);
                    }
                }
            }

            // Identifikasi Spesimen
            $submission->p2Specs()->delete();
            if ($request->has('specs') && is_array($request->specs)) {
                foreach ($request->specs as $spec) {
                    if (! empty($spec['keterangan'])) {
                        $submission->p2Specs()->create([
                            'keterangan' => $spec['keterangan'],
                            'batang_bawah' => $spec['batang_bawah'],
                            'batang_atas' => $spec['batang_atas'],
                            'alasan' => $spec['alasan'],
                        ]);
                    }
                }
            }

            // Prosedur Pelaksanaan
            $submission->p2Procedures()->delete();
            if ($request->has('procedures') && is_array($request->procedures)) {
                foreach ($request->procedures as $proc) {
                    if (! empty($proc['tahap_kegiatan']) || ! empty($proc['kondisi_jaringan'])) {
                        $submission->p2Procedures()->create([
                            'step_number' => $proc['step_number'],
                            'tahap_kegiatan' => $proc['tahap_kegiatan'],
                            'kondisi_jaringan' => $proc['kondisi_jaringan'],
                        ]);
                    }
                }
            }

            // Monitoring Proyek
            $submission->p2Monitorings()->delete();
            if ($request->has('monitorings') && is_array($request->monitorings)) {
                foreach ($request->monitorings as $mon) {
                    if (! empty($mon['aspek'])) {
                        $submission->p2Monitorings()->create([
                            'aspek' => $mon['aspek'],
                            'hasil_pengamatan' => $mon['hasil_pengamatan'],
                        ]);
                    }
                }
            }

            // Pertanyaan Esensial P2
            $submission->p2Questions()->updateOrCreate(
                ['lkm_submission_id' => $submission->id],
                $request->p2questions
            );
        }

        // =============================================================
        // LKM 3 (Sintak 5)
        // =============================================================
        if ($pertemuan == 3) {
            // Pengamatan Pertumbuhan Tunas & Daun
            $submission->p3Growths()->delete();
            if ($request->has('growths') && is_array($request->growths)) {
                foreach ($request->growths as $growth) {
                    if (! empty($growth['parameter'])) {
                        $submission->p3Growths()->create([
                            'parameter' => $growth['parameter'],
                            'data_jumlah' => $growth['data_jumlah'],
                            'deskripsi_kondisi' => $growth['deskripsi_kondisi'],
                        ]);
                    }
                }
            }

            // Pengamatan Kondisi Batang Atas (Scion)
            $oldScions = $submission->p3Scions->keyBy('parameter');
            $submission->p3Scions()->delete();
            if ($request->has('scions') && is_array($request->scions)) {
                foreach ($request->scions as $scion) {
                    if (! empty($scion['parameter'])) {
                        $path = null;

                        if (isset($scion['dokumentasi_file']) && $scion['dokumentasi_file'] instanceof UploadedFile) {
                            $path = $scion['dokumentasi_file']->store('lkm_dokumentasi', 'public');
                        } else {
                            // Reuse old path if no new file uploaded
                            $oldScion = $oldScions->get($scion['parameter']);
                            if ($oldScion) {
                                $path = $oldScion->dokumentasi_path;
                            }
                        }

                        $submission->p3Scions()->create([
                            'parameter' => $scion['parameter'],
                            'kondisi_deskripsi' => $scion['kondisi_deskripsi'] ?? null,
                            'dokumentasi_path' => $path,
                        ]);
                    }
                }
            }

            // Pengamatan Kondisi Batang Bawah (Rootstock)
            $oldRootstocks = $submission->p3Rootstocks->keyBy('parameter');
            $submission->p3Rootstocks()->delete();
            if ($request->has('rootstocks') && is_array($request->rootstocks)) {
                foreach ($request->rootstocks as $rootstock) {
                    if (! empty($rootstock['parameter'])) {
                        $path = null;

                        if (isset($rootstock['dokumentasi_file']) && $rootstock['dokumentasi_file'] instanceof UploadedFile) {
                            $path = $rootstock['dokumentasi_file']->store('lkm_dokumentasi', 'public');
                        } else {
                            // Reuse old path if no new file uploaded
                            $oldRootstock = $oldRootstocks->get($rootstock['parameter']);
                            if ($oldRootstock) {
                                $path = $oldRootstock->dokumentasi_path;
                            }
                        }

                        $submission->p3Rootstocks()->create([
                            'parameter' => $rootstock['parameter'],
                            'kondisi_deskripsi' => $rootstock['kondisi_deskripsi'] ?? null,
                            'dokumentasi_path' => $path,
                        ]);
                    }
                }
            }

            // Pengamatan Kondisi Sambungan
            $submission->p3Connections()->updateOrCreate(
                ['lkm_submission_id' => $submission->id],
                [
                    'rincian_sambungan' => $request->connection['rincian_sambungan'] ?? null,
                    'is_tumbuh_tunas' => $request->connection['is_tumbuh_tunas'] ?? null,
                    'alasan' => $request->connection['alasan'] ?? null,
                ]
            );

            // Pertanyaan Esensial P3
            $submission->p3Questions()->updateOrCreate(
                ['lkm_submission_id' => $submission->id],
                $request->p3questions
            );
        }

        // =============================================================
        // LKM 4 (Sintak 6)
        // =============================================================
        if ($pertemuan == 4) {
            // Analisis Keberhasilan
            $submission->p4Analyses()->delete();
            if ($request->has('analyses') && is_array($request->analyses)) {
                foreach ($request->analyses as $analysis) {
                    if (! empty($analysis['variabel_analisis'])) {
                        $submission->p4Analyses()->create([
                            'variabel_analisis' => $analysis['variabel_analisis'],
                            'hasil_pengamatan' => $analysis['hasil_pengamatan'],
                        ]);
                    }
                }
            }

            // Pertanyaan Analisis Mendalam
            $submission->p4DeepQuestions()->updateOrCreate(
                ['lkm_submission_id' => $submission->id],
                $request->deepQuestions
            );

            // Penilaian Diri
            $submission->p4SelfAssessments()->delete();
            if ($request->has('selfAssessments') && is_array($request->selfAssessments)) {
                foreach ($request->selfAssessments as $assessment) {
                    if (! empty($assessment['aspek'])) {
                        $submission->p4SelfAssessments()->create([
                            'aspek' => $assessment['aspek'],
                            'skor' => $assessment['skor'],
                            'catatan' => $assessment['catatan'],
                        ]);
                    }
                }
            }

            // Refleksi Essay
            $submission->p4Reflections()->updateOrCreate(
                ['lkm_submission_id' => $submission->id],
                $request->reflections
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
