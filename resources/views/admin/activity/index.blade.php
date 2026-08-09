@extends('layouts.admin')

@section('page_title', 'System Activity Audit Logs')

@section('content')
<div class="admin-card p-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-primary me-2"></i> Security & Activity Audit Log</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr class="text-muted small text-uppercase">
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="small text-muted">{{ $log->created_at->format('d M Y, h:i A') }}</td>
                        <td class="fw-bold">{{ $log->user->name ?? 'System' }}</td>
                        <td><span class="badge bg-info text-uppercase">{{ $log->action }}</span></td>
                        <td class="small">{{ $log->description }}</td>
                        <td class="small text-muted">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No activity logs recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
</div>
@endsection
