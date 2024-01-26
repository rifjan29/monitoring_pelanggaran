<?php

namespace App\Http\Controllers;

use App\Models\DataSantri;
use App\Models\DataWaliSantri;
use App\Models\DetailLaporanPondok;
use App\Models\LaporanPondok;
use App\Models\PelanggaranPondok;
use App\Models\PelanggaranSekolah;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LaporanController extends Controller
{
    function index() {
        $param['title'] = 'Pelaporan';
        $param['laporan'] = LaporanPondok::with('detailLaporan')->orderByDesc('created_at')->paginate(10);
        return view('laporan.index',$param);
    }

    function detail($id) {
        $param['title'] = 'Detail Pelaporan';
        $data = DetailLaporanPondok::orderByDesc('created_at')->where('laporan_pondok_id',$id)->paginate(10);
        foreach ($data as $key => $value) {
            if ($value->pelanggaran_sekolah_id != null) {
                $pelanggaran_laporan_sekolah = PelanggaranSekolah::with('santri')->find($value->pelanggaran_sekolah_id);
                $value->pelanggaran_laporan_sekolah = $pelanggaran_laporan_sekolah;
            }
            if ($value->pelanggaran_pondok_id != null) {
                $pelanggaran_laporan_pondok = PelanggaranPondok::with('santri')->find($value->pelanggaran_pondok_id);
                $value->pelanggaran_laporan_pondok = $pelanggaran_laporan_pondok;
            }
        }
        $param['laporan'] = $data;
        return view('laporan.detail',$param);
    }

    function kirimSekolah($id) {
        $santri = PelanggaranSekolah::with('santri')->find($id);
        $santri->wali_santri = DataWaliSantri::find($santri->santri->wali_santri_id);
        $data["email"] = $santri->wali_santri->email;
        $data["title"] = "LEMBAR CATATAN PELANGGARAN SANTRI";
        $data["body"] = "Assalamualaikum, Berikut adalah lembaran Pelanggaran Santri atas nama : ".$santri->santri->nama_lengkap."";
        $pdf = FacadePdf::loadView('laporan.pdf.pdf-sekolah',['data' => $santri]);
        Mail::send('emails.emails-pondok', $data, function($message) use ($data, $pdf) {
            $message->to($data['email'], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), "pelanggaran-sekolah.pdf");
        });
        $update = PelanggaranSekolah::find($id);
        $update->status_kirim = 'selesai';
        $update->update();
        alert()->success('Berhasil mengirimkan email');
        return redirect()->route('laporan.index');
    }

    function kirimPondok($id) {
        $santri = PelanggaranPondok::with('santri')->find($id);
        $santri->wali_santri = DataWaliSantri::find($santri->santri->wali_santri_id);
        $data["email"] = $santri->wali_santri->email;
        $data["title"] = "LEMBAR CATATAN PELANGGARAN SANTRI";
        $data["body"] = "Assalamualaikum, Berikut adalah lembaran Pelanggaran Santri atas nama : ".$santri->santri->nama_lengkap."";
        $pdf = FacadePdf::loadView('laporan.pdf.pdf-pondok',['data' => $santri]);
        Mail::send('emails.emails-pondok', $data, function($message) use ($data, $pdf) {
            $message->to($data['email'], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), "pelanggaran-pondok.pdf");
        });
        $update = PelanggaranPondok::find($id);
        $update->status_kirim = 'selesai';
        $update->update();
        alert()->success('Berhasil mengirimkan email');
        return redirect()->route('laporan.index');
    }

    function generateLaporanWeek() {
        DB::beginTransaction();;
        try {
            $noLaporan = null;
            $tanggalSekarang = Carbon::now();

            $data_laporan = LaporanPondok::whereDate('created_at',$tanggalSekarang)->orderByDesc('created_at')->get();
            $date = Carbon::now()->format('dmy');
            if($data_laporan->count() > 0) {
                // Mengambil bagian kode yang merepresentasikan nomor urutan
                $lastIncrement = (int) substr($data_laporan[0]->kode, -5);

                // Menaikkan nomor urutan
                $nextIncrement = $lastIncrement + 1;

                // Memastikan nomor urutan selalu memiliki 5 digit
                $formattedIncrement = str_pad($nextIncrement, 5, "0", STR_PAD_LEFT);
            }
            else {
                $formattedIncrement = "00001";

            }
            DB::commit();
            $noLaporan = 'LPP' . $date . $formattedIncrement;
            $laporan = new LaporanPondok;
            $laporan->kode_laporan = $noLaporan;
            $laporan->tanggal = Carbon::now();
            $laporan->user_id = Auth::user()->id;
            $laporan->save();

            $pelanggaran_laporan_pondok = PelanggaranPondok::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
            if (count($pelanggaran_laporan_pondok) > 0 ) {
                foreach ($pelanggaran_laporan_pondok as $key => $value) {
                    $detail = new DetailLaporanPondok;
                    $detail->laporan_pondok_id = $laporan->id;
                    $detail->pelanggaran_pondok_id = $value->id;
                    $detail->save();
                }
            }

            $pelanggaran_laporan_sekolah = PelanggaranSekolah::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
            if (count($pelanggaran_laporan_sekolah) > 0 ) {
                foreach ($pelanggaran_laporan_sekolah as $key => $value) {
                    $detail = new DetailLaporanPondok;
                    $detail->laporan_pondok_id = $laporan->id;
                    $detail->pelanggaran_sekolah_id = $value->id;
                    $detail->save();
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        } catch (QueryException $e) {
            DB::rollBack();
            return $e;

        }
    }

    function sendLaporan() {
        $pelanggaran_laporan_pondok = PelanggaranPondok::with('santri')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        if (count($pelanggaran_laporan_pondok) > 0 ) {
            foreach ($pelanggaran_laporan_pondok as $key => $value) {
                $santri =  DataWaliSantri::find($value->santri->wali_santri_id);
                $data["email"] = $santri->wali_santri->email;
                $data["title"] = "LEMBAR CATATAN PELANGGARAN SANTRI";
                $data["body"] = "Assalamualaikum, Berikut adalah lembaran Pelanggaran Santri atas nama : ".$value->santri->nama_lengkap."";
                $pdf = FacadePdf::loadView('laporan.pdf.pdf-sekolah',['data' => $santri]);
                Mail::send('emails.emails-pondok', $data, function($message) use ($data, $pdf) {
                    $message->to($data['email'], $data["email"])
                            ->subject($data["title"])
                            ->attachData($pdf->output(), "pelanggaran-sekolah.pdf");
                });
                $update = PelanggaranSekolah::find($value->id);
                $update->status_kirim = 'selesai';
                $update->update();
            }
        }
        $pelanggaran_laporan_sekolah = PelanggaranSekolah::with('santri')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        if (count($pelanggaran_laporan_sekolah) > 0 ) {
            foreach ($pelanggaran_laporan_sekolah as $key => $value) {
                $santri = DataWaliSantri::find($value->santri->wali_santri_id);
                $data["email"] = $santri->wali_santri->email;
                $data["title"] = "LEMBAR CATATAN PELANGGARAN SANTRI";
                $data["body"] = "Assalamualaikum, Berikut adalah lembaran Pelanggaran Santri atas nama : ".$santri->santri->nama_lengkap."";
                $pdf = FacadePdf::loadView('laporan.pdf.pdf-sekolah',['data' => $santri]);
                Mail::send('emails.emails-pondok', $data, function($message) use ($data, $pdf) {
                    $message->to($data['email'], $data["email"])
                            ->subject($data["title"])
                            ->attachData($pdf->output(), "pelanggaran-sekolah.pdf");
                });
                $update = PelanggaranSekolah::find($value->id);
                $update->status_kirim = 'selesai';
                $update->update();
            }
        }
    }
}
