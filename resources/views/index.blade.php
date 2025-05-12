<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSV Uploads</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<script>
    function fetchUploads() {
        fetch('/api/uploads')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('upload-list');
                tbody.innerHTML = '';
                data.forEach(upload => {
                    const row = `
                        <tr>
                            <td>${upload.created_at_readable}<br>(${upload.created_ago})</td>
                            <td>${upload.filename}</td>
                            <td>${upload.status}</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            });
    }

    fetchUploads();
    setInterval(fetchUploads, 1000);
</script>
<body>
    <h1>Upload CSV</h1>

    <form id="upload-form" action="/upload" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" required>
        <button type="submit">Upload File</button>
    </form>

    <h2>Upload History</h2>
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>File Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="upload-list">
            @foreach ($uploads as $upload)
                <tr>
                    <td>{{ $upload->created_at->format('m-d-Y h:i A') }}<br>({{ $upload->created_at->diffForHumans() }})</td>
                    <td>{{ $upload->filename }}</td>
                    <td>{{ $upload->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        // Basic polling to refresh the upload list every 1 seconds
        setInterval(() => {
            fetch('/api/uploads')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('upload-list');
                    tbody.innerHTML = '';
                    data.forEach(upload => {
                        const row = `<tr>
                            <td>${upload.created_at_readable}<br>(${upload.created_ago})</td>
                            <td>${upload.filename}</td>
                            <td>${upload.status}</td>
                        </tr>`;
                        tbody.innerHTML += row;
                    });
                });
        }, 1000);
    </script>
</body>
</html>
