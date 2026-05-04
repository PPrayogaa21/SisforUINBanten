<?php

namespace App\Services;

use App\Models\Biodata;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiodataApiService
{
    protected string $apiUrl;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.biodata.url', env('BIODATA_API_URL', ''));
        $this->apiKey = config('services.biodata.key', env('BIODATA_API_KEY'));
    }

    /**
     * Check biodata from external API by NIP
     */
    public function checkBiodata(string $nip): ?array
    {
        if (empty($this->apiUrl)) {
            Log::warning('Biodata API URL not configured');
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => $this->apiKey ? 'Bearer ' . $this->apiKey : '',
                ])
                ->get("{$this->apiUrl}/{$nip}");

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']) && !empty($data['data'])) {
                    return $data['data'];
                }
                return $data;
            }

            Log::info("Biodata not found in external API for NIP: {$nip}");
            return null;
        } catch (\Exception $e) {
            Log::error("Failed to fetch biodata from API: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Fetch biodata from API and store locally
     */
    public function fetchAndStore(User $user): ?Biodata
    {
        $apiData = $this->checkBiodata($user->username);

        if (!$apiData) {
            return null;
        }

        $biodata = Biodata::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nip' => $user->username,
                'nama_lengkap' => $apiData['nama_lengkap'] ?? $apiData['nama'] ?? ($user->biodata->nama_lengkap ?? $user->username),
                'jabatan' => $apiData['jabatan'] ?? null,
                'bagian' => $apiData['bagian'] ?? $apiData['unit_kerja'] ?? null,
                'pangkat_golongan' => $apiData['pangkat_golongan'] ?? null,
                'no_hp' => $apiData['no_hp'] ?? null,
                'alamat' => $apiData['alamat'] ?? null,
                'from_api' => true,
            ]
        );

        $user->update(['biodata_verified' => true]);

        return $biodata;
    }
}
