<?php

namespace App\Exports;

use App\Present;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Fromview;

class UsersPresentExport implements Fromview
{

    private $bulan;

    public function __construct($bulan) {
        $this->bulan = $bulan;
    }
    
    public function view(): view
    {
        $data = explode('-',$this->bulan);
        $presents = Present::whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->orderBy('tanggal','desc')->get();
        $kehadiran = Present::whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('telat')->get();
        $totalJamTelat = 0;
        foreach ($kehadiran as $present) {
            $totalJamTelat = $totalJamTelat + (\Carbon\Carbon::parse($present->jam_masuk)->diffInHours(\Carbon\Carbon::parse(config('absensi.jam_masuk'))));
        }
        return view('presents.users-excel', compact('presents','totalJamTelat'));
    }
}
