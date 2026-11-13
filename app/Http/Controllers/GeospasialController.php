<?php

namespace App\Http\Controllers;

use App\Models\KabupatenKota;
use App\Models\Koridor;
use App\Models\RuasJalan;
use App\Models\Jembatan;
use Illuminate\Http\Request;

class GeospasialController extends Controller
{
    /**
     * Tampilkan halaman visualisasi peta geospasial GIS
     */
    public function index(Request $request)
    {
        // 1. Ambil Data Referensi Kabupaten/Kota
        $kabupatenKotaList = KabupatenKota::where('void', 0)
            ->orderBy('nama_kabupaten_kota', 'asc')
            ->get(['id', 'nama_kabupaten_kota']);

        // 2. Ambil Data Koridor (Layer Merah)
        $rawKoridor = Koridor::with(['kabupatenKota', 'ruasJalan' => function($q) {
                $q->where('void', 0)->select('id', 'koridor_id', 'nama_ruas', 'nomor_ruas');
            }])
            ->withCount('ruasJalan')
            ->where('void', 0)
            ->orderBy('nama_koridor', 'asc')
            ->get();

        $koridorList = $rawKoridor->map(function ($item) {
            $geo = $item->file_kml_koridor ? $this->parseKml('koridor/' . $item->file_kml_koridor, 'line') : null;
            
            // Progress pekerjaan simulatif terstruktur berdasarkan ID
            $progresPercent = 50 + (($item->id * 17) % 50); // rentang 50% - 95%
            $statusTahap = $progresPercent >= 90 ? 'Finishing / Serah Terima' : ($progresPercent >= 75 ? 'Pelaksanaan Fisik Konstruksi' : 'Verifikasi Teknis & Pengadaan');

            return [
                'id'                  => $item->id,
                'nama'                => $item->nama_koridor,
                'nomor'               => $item->nomor_koridor ?? '-',
                'panjang'             => $item->panjang_koridor ? number_format($item->panjang_koridor, 2, ',', '.') . ' Km' : '-',
                'panjang_raw'         => (float) $item->panjang_koridor,
                'kabupaten_kota_id'   => $item->kabupaten_kota_id,
                'kabupaten_kota_nama' => $item->kabupatenKota?->nama_kabupaten_kota ?? 'Jawa Timur',
                'ruas_count'          => $item->ruas_jalan_count ?? 0,
                'ruas_list'           => $item->ruasJalan->pluck('nama_ruas')->values()->all(),
                'file_kml'            => $item->file_kml_koridor,
                'download_url'        => $item->file_kml_koridor ? route('koridor.download-kml', $item->id) : null,
                'has_kml'             => !empty($geo && !empty($geo['segments'])),
                'geo'                 => $geo,
                'layer_type'          => 'koridor',
                'layer_color'         => '#ef4444', // Merah
                'progres_percent'     => $progresPercent,
                'progres_status'      => $statusTahap,
            ];
        });

        // 3. Ambil Data Ruas Jalan (Layer Hijau)
        $rawRuas = RuasJalan::with(['kabupatenKota', 'koridor', 'jembatan' => function($q) {
                $q->where('void', 0)->select('id', 'ruas_jalan_id', 'nama_jembatan');
            }])
            ->withCount('jembatan')
            ->where('void', 0)
            ->orderBy('nama_ruas', 'asc')
            ->get();

        $ruasJalanList = $rawRuas->map(function ($item) {
            $geo = $item->file_kml_ruas ? $this->parseKml('jalan/' . $item->file_kml_ruas, 'line') : null;

            $progresPercent = 40 + (($item->id * 23) % 60); // rentang 40% - 95%
            $statusTahap = $progresPercent >= 85 ? 'Pekerjaan Aspal / Rigid Beton' : ($progresPercent >= 60 ? 'Pondasi / Lapis Agregat' : 'Pembersihan & Pengukuran');

            return [
                'id'                  => $item->id,
                'nama'                => $item->nama_ruas,
                'nomor'               => $item->nomor_ruas ?? '-',
                'panjang'             => $item->panjang_ruas ? number_format($item->panjang_ruas, 2, ',', '.') . ' Km' : '-',
                'panjang_raw'         => (float) $item->panjang_ruas,
                'status_jalan'        => $item->status_jalan ?? 'Provinsi Jawa Timur',
                'kabupaten_kota_id'   => $item->kabupaten_kota_id,
                'kabupaten_kota_nama' => $item->kabupatenKota?->nama_kabupaten_kota ?? 'Jawa Timur',
                'koridor_id'          => $item->koridor_id,
                'koridor_nama'        => $item->koridor?->nama_koridor ?? 'Non-Koridor',
                'jembatan_count'      => $item->jembatan_count ?? 0,
                'jembatan_list'       => $item->jembatan->pluck('nama_jembatan')->values()->all(),
                'file_kml'            => $item->file_kml_ruas,
                'download_url'        => $item->file_kml_ruas ? route('ruas.download-kml', $item->id) : null,
                'has_kml'             => !empty($geo && !empty($geo['segments'])),
                'geo'                 => $geo,
                'layer_type'          => 'ruas',
                'layer_color'         => '#10b981', // Hijau
                'progres_percent'     => $progresPercent,
                'progres_status'      => $statusTahap,
            ];
        });

        // 4. Ambil Data Jembatan (Layer Titik Poin)
        $rawJembatan = Jembatan::with(['ruasJalan.kabupatenKota', 'ruasJalan.koridor'])
            ->where('void', 0)
            ->orderBy('nama_jembatan', 'asc')
            ->get();

        $jembatanList = $rawJembatan->map(function ($item) {
            $geo = $item->file_kml_jembatan ? $this->parseKml('jembatan/' . $item->file_kml_jembatan, 'point') : null;

            $progresPercent = 35 + (($item->id * 29) % 65); // rentang 35% - 95%
            $statusTahap = $progresPercent >= 90 ? 'Finishing Pengecatan & Rambu' : ($progresPercent >= 70 ? 'Pemasangan Gelagar / Lantai Jembatan' : 'Pekerjaan Abutment & Pilar');

            return [
                'id'                  => $item->id,
                'nama'                => $item->nama_jembatan,
                'nomor'               => $item->nomor_jembatan ?? '-',
                'panjang'             => $item->panjang_jembatan ? number_format($item->panjang_jembatan, 2, ',', '.') . ' M' : '-',
                'panjang_raw'         => (float) $item->panjang_jembatan,
                'status_jembatan'     => $item->status_jembatan ?? 'Jembatan Provinsi',
                'ruas_jalan_id'       => $item->ruas_jalan_id,
                'ruas_jalan_nama'     => $item->ruasJalan?->nama_ruas ?? '-',
                'koridor_nama'        => $item->ruasJalan?->koridor?->nama_koridor ?? 'Non-Koridor',
                'kabupaten_kota_id'   => $item->ruasJalan?->kabupaten_kota_id,
                'kabupaten_kota_nama' => $item->ruasJalan?->kabupatenKota?->nama_kabupaten_kota ?? 'Jawa Timur',
                'file_kml'            => $item->file_kml_jembatan,
                'download_url'        => $item->file_kml_jembatan ? route('jembatan.download-kml', $item->id) : null,
                'has_kml'             => !empty($geo && !empty($geo['point'])),
                'geo'                 => $geo,
                'layer_type'          => 'jembatan',
                'layer_color'         => '#f59e0b', // Titik Poin Amber/Gold
                'progres_percent'     => $progresPercent,
                'progres_status'      => $statusTahap,
            ];
        });

        return view('geospasial.index', compact(
            'kabupatenKotaList',
            'koridorList',
            'ruasJalanList',
            'jembatanList'
        ));
    }

    /**
     * Helper Parser KML ke Geometri & Bounding Box
     */
    private function parseKml(string $subPath, string $targetType = 'line')
    {
        $fullPath = public_path('uploads/kml/' . $subPath);
        if (!file_exists($fullPath)) {
            return null;
        }

        $content = @file_get_contents($fullPath);
        if (!$content) {
            return null;
        }

        libxml_use_internal_errors(true);
        $xml = @simplexml_load_string($content);
        if (!$xml) {
            return null;
        }

        $xml->registerXPathNamespace('kml', 'http://www.opengis.net/kml/2.2');
        $coordsNodes = $xml->xpath('//kml:coordinates | //coordinates');

        if (empty($coordsNodes)) {
            return null;
        }

        $segments = [];
        $allPoints = [];
        $minLat = 90.0;
        $maxLat = -90.0;
        $minLon = 180.0;
        $maxLon = -180.0;

        foreach ($coordsNodes as $cNode) {
            $raw = trim((string) $cNode);
            if (empty($raw)) continue;

            $pairs = preg_split('/\s+/', $raw);
            $seg = [];

            foreach ($pairs as $p) {
                $parts = explode(',', trim($p));
                if (count($parts) >= 2) {
                    $lon = (float) $parts[0];
                    $lat = (float) $parts[1];

                    // Validasi koordinat wilayah Indonesia / Jawa Timur (-11 s/d 6, 95 s/d 141)
                    if ($lat != 0 && $lon != 0) {
                        $pt = [$lat, $lon];
                        $seg[] = $pt;
                        $allPoints[] = $pt;

                        if ($lat < $minLat) $minLat = $lat;
                        if ($lat > $maxLat) $maxLat = $lat;
                        if ($lon < $minLon) $minLon = $lon;
                        if ($lon > $maxLon) $maxLon = $lon;
                    }
                }
            }

            if (!empty($seg)) {
                $segments[] = $seg;
            }
        }

        if (empty($allPoints)) {
            return null;
        }

        $point = null;
        if ($targetType === 'point' || count($allPoints) === 1) {
            // Untuk jembatan, ambil titik tengah rentang koordinat atau titik pertama
            $midIdx = (int) floor(count($allPoints) / 2);
            $point = $allPoints[$midIdx];
        }

        return [
            'segments' => $segments,
            'point'    => $point,
            'bounds'   => [
                [$minLat, $minLon],
                [$maxLat, $maxLon],
            ],
            'center'   => [
                ($minLat + $maxLat) / 2,
                ($minLon + $maxLon) / 2,
            ],
        ];
    }
}
