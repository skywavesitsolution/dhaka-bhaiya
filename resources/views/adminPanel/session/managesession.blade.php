@extends('adminPanel.master')

@section('style')
    <!-- Removed DataTables and Bootstrap CSS; rely on adminPanel.master -->
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right"></div>
                    <h4 class="page-title">Manage Sessions</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5 fw-bold">List Session</div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#create-session-modal">
                                        Create New Session
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive m-1">
                        <table class="table table-centered w-100 nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Session Date</th>
                                    <th>Start Date</th>
                                    <th>Start Time</th>
                                    <th>End Date</th>
                                    <th>End Time</th>
                                    <th>Status</th>
                                    <th>User</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sessions as $session)
                                    <tr>
                                        <td>{{ $session->id }}</td>
                                        <td>{{ $session->session_date }}</td>
                                        <td>{{ $session->start_date }}</td>
                                        <td>{{ $session->start_time }}</td>
                                        <td>{{ $session->end_date ?? 'N/A' }}</td>
                                        <td>{{ $session->end_time ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $session->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $session->status }}
                                            </span>
                                        </td>
                                        <td>{{ $session->user->name }}</td>
                                        <td>
                                            @if ($session->status == 'active')
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#end-session-modal" data-id="{{ $session->id }}">
                                                    End Session
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Create Session Modal -->
                    <div id="create-session-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="create-session-modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="create-session-modalLabel">Create New Session</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                </div>
                                <form id="createSessionForm" action="{{ route('session.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="mb-3 col-sm-6">
                                                <label for="session_date" class="form-label">Session Date</label>
                                                <input type="date" class="form-control" id="session_date" name="session_date" value="{{ date('Y-m-d') }}" readonly>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="start_date" class="form-label">Start Date</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ date('Y-m-d') }}" readonly>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="start_time" class="form-label">Start Time</label>
                                                <input type="time" class="form-control" id="start_time" name="start_time" value="{{ date('H:i') }}" readonly>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="user_id" class="form-label">User</label>
                                                <input type="text" class="form-control" id="user_id" name="user_id" value="{{ Auth::user()->id }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Create Session</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- End Session Modal -->
                    <div id="end-session-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="end-session-modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="end-session-modalLabel">End Session</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                </div>
                                <form id="end-session-form" method="POST" action="">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <input type="hidden" name="session_id" id="session-id-field">
                                        <div class="row">
                                            <div class="mb-3 col-sm-6">
                                                <label for="end_date" class="form-label">End Date</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="end_time" class="form-label">End Time</label>
                                                <input type="time" class="form-control" id="end_time" name="end_time" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">End Session</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery and SweetAlert2 only; assume Bootstrap JS is in adminPanel.master -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            console.log('Document ready');

            // Handle Create Session Form submission
            $('#createSessionForm').on('submit', function(e) {
                e.preventDefault();
                console.log('Create form submitted with data:', $(this).serialize());

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                    console.log('Create success:', response);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Session Created',
                            text: 'Session Is Created. Now You Can Access POS!',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                },
                    error: function(xhr) {
                        console.log('Create error:', xhr.responseText);
                        let errorMsg = 'Something went wrong. Please try again.';
                        if (xhr.status === 400) {
                            errorMsg = JSON.parse(xhr.responseText).error;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg,
                        });
                    }
                });
            });

            // Handle End Session Modal show event
            $('#end-session-modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var sessionId = button.data('id');
                var form = $(this).find('#end-session-form');
                var action = '{{ route('session.end', ['id' => '__ID__']) }}'.replace('__ID__', sessionId);
                form.attr('action', action);
                console.log('End modal opened, action set to:', action);
                $(this).find('#session-id-field').val(sessionId);

                // Set current date and time
                var now = new Date();
                var currentDate = now.toISOString().split('T')[0];
                var currentTime = now.toTimeString().slice(0, 5);
                $('#end_date').val(currentDate);
                $('#end_time').val(currentTime);
            });

            // Handle End Session Form submission
            $('#end-session-form').on('submit', function(e) {
                e.preventDefault();
                console.log('End form submitted with data:', $(this).serialize());
                console.log('Submitting to:', $(this).attr('action'));

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'PATCH',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('End success:', response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Session Ended',
                            text: 'The Session Has Been Ended Successfully! AND You Cannnot Acces POS! Now',
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.log('End error status:', xhr.status);
                        console.log('End error response:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to end session: ' + (xhr.responseText || 'Unknown error'),
                        });
                    }
                });
            });

            // Display session error if exists
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Session Required',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Create Session'
                });
            @endif
        });
    </script>
@endsection