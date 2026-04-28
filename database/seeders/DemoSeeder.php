<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Biodata;
use App\Models\Kegiatan;
use App\Models\KegiatanMateri;
use App\Models\Kuesioner;
use App\Models\KuesionerPertanyaan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users
        $users = [];
        $demoData = [
            ['nip' => '198501012010011001', 'name' => 'Dr. Ahmad Fauzi, M.Kom.', 'email' => 'ahmad.fauzi@sitsfor.ac.id', 'jabatan' => 'Dosen', 'unit_kerja' => 'Fakultas Sains dan Teknologi'],
            ['nip' => '199001012015012001', 'name' => 'Siti Nurhaliza, S.Pd., M.Pd.', 'email' => 'siti.nurhaliza@sitsfor.ac.id', 'jabatan' => 'Dosen', 'unit_kerja' => 'Fakultas Tarbiyah dan Keguruan'],
            ['nip' => '197505152003121003', 'name' => 'Prof. Dr. H. Budi Santoso', 'email' => 'budi.santoso@sitsfor.ac.id', 'jabatan' => 'Guru Besar', 'unit_kerja' => 'Fakultas Ushuluddin'],
            ['nip' => '199203052020011001', 'name' => 'Rizki Pratama, S.Kom., M.T.', 'email' => 'rizki.pratama@sitsfor.ac.id', 'jabatan' => 'Dosen', 'unit_kerja' => 'Fakultas Sains dan Teknologi'],
            ['nip' => '198812102018012001', 'name' => 'Dewi Anggraini, S.E., M.M.', 'email' => 'dewi.anggraini@sitsfor.ac.id', 'jabatan' => 'Staf', 'unit_kerja' => 'Fakultas Ekonomi dan Bisnis Islam'],
        ];

        foreach ($demoData as $data) {
            $user = User::create([
                'nip' => $data['nip'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'user',
                'biodata_verified' => true,
                'email_verified_at' => now(),
            ]);

            Biodata::create([
                'user_id' => $user->id,
                'nip' => $data['nip'],
                'nama_lengkap' => $data['name'],
                'jabatan' => $data['jabatan'],
                'unit_kerja' => $data['unit_kerja'],
                'pangkat_golongan' => 'III/c',
                'no_hp' => '08' . rand(1000000000, 9999999999),
                'from_api' => true,
            ]);

            $users[] = $user;
        }

        // Create demo kegiatan
        $admin = User::where('role', 'admin')->first();

        $kegiatanData = [
            [
                'nama_kegiatan' => 'Seminar Nasional Transformasi Digital Pendidikan Tinggi',
                'jenis' => 'seminar',
                'deskripsi' => 'Seminar nasional yang membahas transformasi digital di lingkungan pendidikan tinggi Indonesia, mencakup strategi implementasi teknologi informasi, e-learning, dan manajemen data akademik.',
                'waktu_mulai' => now()->addDays(7)->setTime(8, 0),
                'waktu_selesai' => now()->addDays(7)->setTime(16, 0),
                'tempat' => 'Auditorium Utama Kampus',
                'latitude' => -6.9175,
                'longitude' => 107.6191,
                'alamat_lengkap' => 'Jl. A.H. Nasution No. 105, Bandung',
                'status' => 'published',
            ],
            [
                'nama_kegiatan' => 'Workshop Pengembangan Kurikulum Berbasis KKNI',
                'jenis' => 'workshop',
                'deskripsi' => 'Workshop intensif untuk pengembangan kurikulum program studi berbasis Kerangka Kualifikasi Nasional Indonesia (KKNI) dan Merdeka Belajar.',
                'waktu_mulai' => now()->addDays(14)->setTime(9, 0),
                'waktu_selesai' => now()->addDays(14)->setTime(15, 0),
                'tempat' => 'Ruang Rapat Lt. 3 Gedung Rektorat',
                'latitude' => -6.9200,
                'longitude' => 107.6200,
                'alamat_lengkap' => 'Jl. A.H. Nasution No. 105, Bandung',
                'status' => 'published',
            ],
            [
                'nama_kegiatan' => 'Rapat Koordinasi Pimpinan Semester Genap 2025/2026',
                'jenis' => 'rapat',
                'deskripsi' => 'Rapat koordinasi rutin pimpinan universitas untuk membahas program kerja semester genap tahun akademik 2025/2026.',
                'waktu_mulai' => now()->subDays(5)->setTime(10, 0),
                'waktu_selesai' => now()->subDays(5)->setTime(12, 0),
                'tempat' => 'Ruang Sidang Utama',
                'latitude' => -6.9180,
                'longitude' => 107.6195,
                'status' => 'completed',
            ],
            [
                'nama_kegiatan' => 'Pelatihan Penulisan Artikel Ilmiah Internasional',
                'jenis' => 'pelatihan',
                'deskripsi' => 'Pelatihan intensif penulisan dan publikasi artikel ilmiah di jurnal internasional bereputasi (Scopus, Web of Science).',
                'waktu_mulai' => now()->addDays(21)->setTime(8, 30),
                'waktu_selesai' => now()->addDays(22)->setTime(16, 0),
                'tempat' => 'Lab Komputer Fakultas Sains dan Teknologi',
                'latitude' => -6.9210,
                'longitude' => 107.6185,
                'status' => 'draft',
            ],
        ];

        foreach ($kegiatanData as $kData) {
            $kegiatan = Kegiatan::create(array_merge($kData, ['created_by' => $admin->id]));

            // Add peserta
            $pesertaIds = collect($users)->random(rand(3, 5))->pluck('id');
            foreach ($pesertaIds as $pid) {
                $kegiatan->peserta()->attach($pid, ['status_kehadiran' => $kegiatan->status === 'completed' ? 'hadir' : 'registered']);
            }

            // Add narasumber
            $narasumber = collect($users)->random(rand(1, 2));
            foreach ($narasumber as $n) {
                $kegiatan->narasumber()->syncWithoutDetaching([
                    $n->id => ['topik_materi' => 'Materi ' . ucfirst($kegiatan->jenis)]
                ]);
            }

            // Add kuesioner for completed kegiatan
            if ($kegiatan->status === 'completed') {
                $kuesioner = Kuesioner::create([
                    'kegiatan_id' => $kegiatan->id,
                    'judul' => 'Evaluasi ' . $kegiatan->nama_kegiatan,
                    'is_active' => true,
                ]);

                $pertanyaan = [
                    ['pertanyaan' => 'Bagaimana penilaian Anda terhadap materi yang disampaikan?', 'tipe' => 'rating'],
                    ['pertanyaan' => 'Bagaimana penilaian Anda terhadap narasumber?', 'tipe' => 'rating'],
                    ['pertanyaan' => 'Bagaimana penilaian Anda terhadap fasilitas dan tempat kegiatan?', 'tipe' => 'rating'],
                    ['pertanyaan' => 'Apakah kegiatan ini bermanfaat bagi tugas Anda?', 'tipe' => 'rating'],
                    ['pertanyaan' => 'Saran dan masukan untuk kegiatan selanjutnya', 'tipe' => 'text'],
                ];

                foreach ($pertanyaan as $i => $p) {
                    KuesionerPertanyaan::create(array_merge($p, [
                        'kuesioner_id' => $kuesioner->id,
                        'urutan' => $i + 1,
                    ]));
                }
            }
        }
    }
}
