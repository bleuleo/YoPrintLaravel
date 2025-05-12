<?php
use App\Models\Upload;

Route::get('/uploads', function () {
    return Upload::latest()->get()->map(function ($upload) {
        return [
            'filename' => $upload->filename,
            'status' => $upload->status,
            'created_at_readable' => $upload->created_at->format('m-d-Y h:i A'),
            'created_ago' => $upload->created_at->diffForHumans(),
        ];
    });
});


