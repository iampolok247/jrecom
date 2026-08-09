@extends('layouts.admin')

@section('page_title', 'Customer Account Management')

@section('content')
<div class="admin-card p-4">
    <h5 class="fw-bold mb-3">Registered Customers</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr class="text-muted small text-uppercase">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total Orders</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $cust)
                    <tr>
                        <td class="fw-bold text-dark">{{ $cust->name }}</td>
                        <td>{{ $cust->email }}</td>
                        <td>{{ $cust->phone ?? 'N/A' }}</td>
                        <td><span class="badge bg-primary rounded-pill">{{ $cust->orders_count }} Orders</span></td>
                        <td><span class="badge bg-{{ $cust->status ? 'success' : 'danger' }}">{{ $cust->status ? 'Active' : 'Blocked' }}</span></td>
                        <td>
                            <form action="{{ route('admin.customers.toggle_status', $cust->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-{{ $cust->status ? 'warning' : 'success' }} rounded-pill">
                                    {{ $cust->status ? 'Block User' : 'Activate User' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $customers->links() }}
</div>
@endsection
