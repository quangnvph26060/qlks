<?php
namespace App\Jobs;

use App\Imports\RoomsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProcessRoomImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle(): void
    {
        try {
            Excel::import(new RoomsImport,  $this->path);
        } catch (\Throwable $e) {
            \Log::error('❌ Lỗi khi import file Excel: ' . $e->getMessage());
            return;
        }

    }
}
