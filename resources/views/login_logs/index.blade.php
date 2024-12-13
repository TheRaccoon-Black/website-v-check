@extends('pemeriksaans.template.template')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Login Logs</h2>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>IP Address</th>
                    <th>Device Info</th>
                    <th>Logged In At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->user->name ?? 'N/A' }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>
                            @php
                                $agent = $log->user_agent;

                                // Deteksi Sistem Operasi
                                if (str_contains($agent, 'Windows')) {
                                    $os = 'Windows';
                                } elseif (str_contains($agent, 'Macintosh')) {
                                    $os = 'macOS';
                                } elseif (str_contains($agent, 'Linux')) {
                                    $os = 'Linux';
                                } elseif (str_contains($agent, 'Android')) {
                                    $os = 'Android';
                                } elseif (str_contains($agent, 'iPhone')) {
                                    $os = 'iOS';
                                } else {
                                    $os = 'Unknown OS';
                                }

                                // Deteksi Browser
                                if (str_contains($agent, 'Edg') || str_contains($agent, 'Edge')) {
                                    $browser = 'Microsoft Edge';
                                } elseif (str_contains($agent, 'Chrome')) {
                                    $browser = 'Google Chrome';
                                } elseif (str_contains($agent, 'Firefox')) {
                                    $browser = 'Mozilla Firefox';
                                } elseif (str_contains($agent, 'Safari') && !str_contains($agent, 'Chrome')) {
                                    $browser = 'Apple Safari';
                                } elseif (str_contains($agent, 'Opera') || str_contains($agent, 'OPR')) {
                                    $browser = 'Opera';
                                } else {
                                    $browser = 'Unknown Browser';
                                }
                            @endphp
                            {{ $browser }} on {{ $os }}

                        </td>
                        <td>{{ \Carbon\Carbon::parse($log->logged_in_at)->format('d M Y, H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No logs available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $logs->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
