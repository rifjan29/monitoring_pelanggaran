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

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('css')
        <style>
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                background-color: #FAFAFA;
                font-family: 'Tinos', serif;
                font: 12pt;
            }
            * {
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }
            p, table, ol{
                font-size: 9pt;
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

                <div class="text-center font-bold text-lg p-5">
                    <h4>LEMBAR CATATAN PELANGGARAN SANTRI</h4>
                    <h5>PONDOK PESANTREN Miftahul Ulum</h5>
                </div>
                <div class="flex justify-center">
                    <button onclick="history.back()" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 no-print"><i class="ti-angle-left btn-icon-prepend"></i> Kembali</button>
                </div>
                <div class="relative  p-5">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama Santri
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Jenis Pelanggaran
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status Pelanggaran
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tanggal Pelanggaran
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Keterangan
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">
                                        {{ $loop->iteration }}
                                    </td>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ ucwords($item->santri->nama_lengkap) }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ ucwords($item->jenis_pelanggaran) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($item->status_pelanggaran == 'sp1')
                                            Surat Peringatan Pertama
                                        @elseif ($item->status_pelanggaran == 'sp2')
                                            Surat Peringatan Kedua
                                        @else
                                            Surat Peringatan Ketiga
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pelanggaran)->format('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item->keterangan_pelanggaran }}
                                    </td>
                                </tr>

                            @empty

                            @endforelse

                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </body>
    <script>
        print();
    </script>
</html>
