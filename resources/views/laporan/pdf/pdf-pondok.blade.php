<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        @stack('css')
        <style>
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                background-color: #FAFAFA;
                font-family: 'Tinos', serif;
                font: 18pt;
            }

            #customers {
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            #customers td, #customers th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            #customers tr:nth-child(even){background-color: #f2f2f2;}

            #customers tr:hover {background-color: #ddd;}

            #customers th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #04AA6D;
                color: white;
            }
            * {
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }
            p, table, ol{
                font-size: 12pt;
            }

            @page {
                margin: 0;  /* Ini akan diterapkan ke setiap halaman */
                size: potrait;
            }

            @page :first {
                margin-top: 10mm;  /* Hanya diterapkan ke halaman pertama */
            }
            @media print {
                /* Sembunyikan thead di semua halaman */
                thead {
                    display: table-header-group;
                }

                thead.no-print {
                    display: none;
                }

                @page {
                    /* Hanya tampilkan thead di halaman pertama */
                    margin-top: 0;
                }

                @page :not(:first) {
                    margin-top: 0;
                }
                /* html, body {
                    width: 210mm;
                    height: 297mm;
                } */
                .no-print, .no-print *
                {
                    display: none !important;
                }
            /* ... the rest of the rules ... */
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Page Content -->
            <main>

                <div class="text-center p-5">
                    <h5 class="fw-bold">LEMBAR CATATAN PELANGGARAN SANTRI</h5>
                    <h5 class="fw-bold">PONDOK PESANTREN MIFTAHUL ULUM</h5>
                </div>
                <div class="container">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-responsive-sm" id="customers">
                                <tbody>
                                    <tr>
                                        <td width="30%">Nama Lengkap</td>
                                        <td width="1%">:</td>
                                        <td >{{ ucwords($data->santri->nama_lengkap) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Jenis Kelamin</td>
                                        <td width="1%">:</td>
                                        <td >
                                            {{ $data->santri->jenis_kelamin == 'l' ? 'Laki-Laki' : 'Perempuan'  }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Asal</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->santri->asal }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Wali Santri</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->wali_santri->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Jenis Pelanggaran</td>
                                        <td width="1%">:</td>
                                        <td>{{ $data->jenis_pelanggaran }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Jumlah Kehadiran</td>
                                        <td width="1%">:</td>
                                        <td>{{ $data->jumlah_kehadiran != null ? $data->jumlah_kehadiran :  '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Jumlah Absen</td>
                                        <td width="1%">:</td>
                                        <td>{{ $data->jumlah_absen  != null ? $data->jumlah_absen : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Keterangan Kehadiran</td>
                                        <td width="1%">:</td>
                                        <td>{{ $data->keterangan_hadir != null ? $data->keterangan_hadir : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Tanggal Pelanggaran</td>
                                        <td width="1%">:</td>
                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_pelanggaran)->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Keterangan Pelanggaran</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->keterangan_pelanggaran }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
