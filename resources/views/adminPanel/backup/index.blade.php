@extends('adminPanel/master')

@section('style')
    <style>
        .backup-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs .nav-link {
            font-weight: 500;
            color: #333;
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            border-color: #007bff;
        }

        .btn-backup,
        .btn-upload {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
        }

        .btn-backup:hover,
        .btn-upload:hover {
            background-color: #0056b3;
        }

        .upload-form {
            margin-top: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container backup-container">
        <h2 class="text-center mb-4">Database Backup Management</h2>
        <ul class="nav nav-tabs mb-4" id="backupTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="download-tab" data-bs-toggle="tab" data-bs-target="#download"
                    type="button" role="tab" aria-controls="download" aria-selected="true">Download Backup</button>
            </li>
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab" aria-controls="upload" aria-selected="false">Upload Local</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="push-online-tab" data-bs-toggle="tab" data-bs-target="#push-online" type="button" role="tab" aria-controls="push-online" aria-selected="false">Upload Online</button>
            </li> --}}
        </ul>
        <div class="tab-content" id="backupTabsContent">
            <div class="tab-pane fade show active" id="download" role="tabpanel" aria-labelledby="download-tab">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Download Database Backup</h5>
                        <p class="card-text">Click the button below to download a .zip file containing your database backup.
                            Choose your desired location in the browser's "Save As" dialog.</p>
                        <button class="btn btn-backup" onclick="triggerBackup()">Download Backup</button>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="upload" role="tabpanel" aria-labelledby="upload-tab">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Upload and Restore Database</h5>
                        <p class="card-text">Upload a .sql file to replace the current database. <strong>Warning:</strong>
                            This will wipe all existing data in the current database.</p>
                        <form id="uploadForm" enctype="multipart/form-data" class="upload-form">
                            <input type="file" name="sql_file" accept=".sql" class="form-control mb-3" required>
                            <button type="button" class="btn btn-upload" onclick="triggerUpload()">Upload to Local</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="push-online" role="tabpanel" aria-labelledby="push-online-tab">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Push Backup to Online Database</h5>
                        <p class="card-text">Click below to push your local database to the online server.
                            <strong>Warning:</strong> This will replace the online database.</p>
                        <button class="btn btn-backup" onclick="triggerPushOnline()">Upload to Online</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function triggerBackup() {
            Swal.fire({
                title: 'Generating Backup',
                text: 'Please wait while the backup is being prepared...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route('backup-restore.download') }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Backup failed');
                    }
                    const fileName = response.headers.get('X-Backup-File');
                    if (!fileName) {
                        throw new Error('Backup file name not provided');
                    }
                    return response.blob().then(blob => ({
                        blob,
                        fileName
                    }));
                })
                .then(({
                    blob,
                    fileName
                }) => {
                    Swal.close();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = fileName.replace('.sql', '.zip');
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Backup downloaded successfully!',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Upload Online'
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire({
                                title: 'Pushing Database',
                                text: 'Please wait while the database is being pushed...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            fetch('{{ route('backup-restore.push-online') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        fileName: fileName
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Swal.close();
                                    if (data.error) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: data.error
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Database pushed successfully!'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to push database. Please try again.'
                                    });
                                });
                        } else {
                            location.reload();
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to generate backup: ' + error.message
                    });
                });
        }

        function triggerUpload() {
            const form = document.getElementById('uploadForm');
            const fileInput = form.querySelector('input[name="sql_file"]');
            if (!fileInput.files.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No File Selected',
                    text: 'Please select a .sql file to upload.'
                });
                return;
            }

            Swal.fire({
                title: 'Confirm Database Restore',
                text: 'This will wipe all existing data in the current database and replace it with the uploaded .sql file. Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Restore',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Restoring Database',
                        text: 'Please wait while the database is being restored...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const formData = new FormData(form);
                    fetch('{{ route('backup-restore.upload') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();
                            if (data.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.error
                                });
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Database restored successfully!'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to restore database. Please try again or contact support.'
                            });
                        });
                }
            });
        }

        function triggerPushOnline() {
            Swal.fire({
                title: 'Confirm Push to Online',
                text: 'This will wipe all data in the online database and replace it with your local database. Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Push',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Pushing Database',
                        text: 'Please wait while the database is being pushed...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route('backup-restore.push-online-seperate') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();
                            if (data.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.error
                                });
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Database pushed successfully!'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to push database. Please try again or contact support.'
                            });
                        });
                }
            });
        }
    </script>
@endsection
