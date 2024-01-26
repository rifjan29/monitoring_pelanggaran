<?php

namespace App\Console\Commands;

use App\Http\Controllers\LaporanController;
use Illuminate\Console\Command;

class SendLaporanTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-laporan-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $laporan = new LaporanController;
        $laporan->sendLaporan();
        $this->info('Send Laporan success');
    }
}
