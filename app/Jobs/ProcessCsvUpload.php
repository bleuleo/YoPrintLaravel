<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Upload;
use App\Models\Product;


class ProcessCsvUpload implements ShouldQueue
{
    use Queueable;

    protected $upload;

    /**
     * Create a new job instance.
     */
    public function __construct(Upload $upload)
    {
       $this->upload = $upload;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->upload->update(['status' => 'processing']);

        $path = storage_path("app/{$this->upload->filename}");
        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            $row = array_combine($header, array_map('trim', $row));
            $row = array_map(fn($v) => mb_convert_encoding($v, 'UTF-8', 'UTF-8'), $row);

            Product::updateOrCreate(
                ['unique_key' => $row['UNIQUE_KEY']],
                [
                    'product_title' => $row['PRODUCT_TITLE'],
                    'product_description' => $row['PRODUCT_DESCRIPTION'],
                    'style' => $row['STYLE#'],
                    'sanmar_mainframe_color' => $row['SANMAR_MAINFRAME_COLOR'],
                    'size' => $row['SIZE'],
                    'color_name' => $row['COLOR_NAME'],
                    'piece_price' => $row['PIECE_PRICE'],
                ]
            );
        }

        $this->upload->update(['status' => 'completed']);
    }
}
