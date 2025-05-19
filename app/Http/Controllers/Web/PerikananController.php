<?php

namespace App\Http\Controllers\Web;

use App\Models\DP;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class PerikananController extends Controller
{
    // View
    public function index()
    {
        $periodeUnikNama = DP::select(DB::raw('DISTINCT DATE_FORMAT(tanggal_input, "%Y-%m") as periode'))
        ->get()
        ->map(function ($item) {
            $carbonDate = Carbon::createFromFormat('Y-m', $item->periode);
            $item->periode_indonesia = $carbonDate->translatedFormat('F Y');
            return $item->periode_indonesia;
        });

        // $dp = DP::all();

        $dp = DP::whereMonth('tanggal_input', 4)
            ->whereYear('tanggal_input', 2025)
            ->distinct()
            ->pluck('jenis_ikan_id');

        return view('admin.perikanan.admin-perikanan', [
            'title' => 'Data Aktivitas Produksi Ikan',
            'data' => $dp,
            'fishes' => DP::select('jenis_ikan_id')->distinct()->pluck('jenis_ikan_id'),
            'periods' => $periodeUnikNama,
        ]);
    }

    // Create
    public function create()
    {
        return view('admin.perikanan.admin-create-perikanan', [
            'title' => 'Tambah Data'
        ]);
    }

    public function store(Request $request)
    {
        //
    }
    
    public function show(DP $dP)
    {
        //
    }    

    public function edit(DP $perikanan)
    {
        return view('admin.perikanan.admin-update-perikanan', [
            'title' => 'Ubah Data',
            'data' => $perikanan,
        ]);
    }

    public function update(Request $request, DP $dP)
    {
        //
    }

    public function destroy(DP $dP)
    {
        //
    }
    
    public function detail()
    {
        Carbon::setLocale('id');

        $periodeUnikAngka = DP::select(DB::raw('DISTINCT DATE_FORMAT(tanggal_input, "%Y-%m") as periode'))
            ->get()
            ->map(function ($item) {
                return $item->periode;
            });

        $periodeUnikNama = $periodeUnikAngka->map(function ($periode) {
            $carbonDate = Carbon::createFromFormat('Y-m', $periode);
            return $carbonDate->translatedFormat('F Y');
        });

        if ($periodeUnikAngka->isEmpty()) {
            return view('admin.perikanan.admin-perikanan-detail', [
                'title' => 'Dinas Perikanan',
                'data' => [],
                'fishes' => [],
                'periods' => [],
                'numberPeriods' => [],
                'daysInMonth' => 0,
            ]);
        }

        $periodeAktif = $periodeUnikAngka[1] ?? $periodeUnikAngka[0];
        $periodeParts = explode('-', $periodeAktif);
        $jumlahHari = Carbon::createFromDate($periodeParts[0], $periodeParts[1])->daysInMonth;

        $dpProduksiHari = DP::whereRaw('DATE_FORMAT(tanggal_input, "%Y-%m") = ?', [$periodeAktif])
            ->get()
            ->groupBy('jenis_ikan_id')
            ->map(function ($items) {
                $row = [
                    'id' => $items[0]->id,
                    'user_id' => $items[0]->user_id,
                    'jenis_ikan_id' => $items[0]->jenis_ikan_id,
                    'ton_produksi' => $items[0]->ton_produksi,
                    'data_asli' => $items, // Untuk debugging/detail
                ];

                foreach ($items as $item) {
                    $tanggal = (int) date('d', strtotime($item->tanggal_input));
                    $row['produksi_per_tanggal'][$tanggal] = $item->ton_produksi;
                }

                return $row;
            })->values();

        $dpProduksiBulanan = DP::get()
            ->groupBy('jenis_ikan_id')
            ->map(function ($items) {
                $row = [
                    'jenis_ikan_id' => $items[0]->jenis_ikan_id,
                    'produksi_per_bulan' => [],
                ];
        
                foreach ($items as $item) {
                    $bulan = (int) date('m', strtotime($item->tanggal_input));
                    if (!isset($row['produksi_per_bulan'][$bulan])) {
                        $row['produksi_per_bulan'][$bulan] = 0;
                    }
                    $row['produksi_per_bulan'][$bulan] += $item->ton_produksi;
                }
        
                return $row;
            })->values();               

        return view('admin.perikanan.admin-perikanan-detail', [
            'title' => 'Dinas Perikanan',
            // 'data' => $dpProduksiHari,
            'data' => $dpProduksiBulanan,
            'fishes' => DP::select('jenis_ikan_id')->distinct()->pluck('jenis_ikan_id'),
            'periods' => $periodeUnikNama,
            'numberPeriods' => $periodeUnikAngka,
            'daysInMonth' => $jumlahHari,
        ]);
    }
}