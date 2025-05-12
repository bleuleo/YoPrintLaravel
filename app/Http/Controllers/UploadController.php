<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Upload;
use App\Jobs\ProcessCsvUpload;

class UploadController extends Controller
{
    public function index()
    {
         $uploads = \App\Models\Upload::latest()->get();
        return view('index', compact('uploads'));
    }

    public function store(Request $request)
    {
        $file = $request->file('csv_file');
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('uploads', $filename);

        $upload = Upload::create(['filename' => $path]);

        dispatch(new \App\Jobs\ProcessCsvUpload($upload));

        return back()->with('success', 'File uploaded. Processing in background.');
    }
}
