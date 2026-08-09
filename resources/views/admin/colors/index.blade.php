@extends('layouts.admin')

@section('page_title', 'Color Options Management')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-palette text-primary me-2"></i> Add New Custom Color</h5>
            <form action="{{ route('admin.colors.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Color Name *</label>
                    <input type="text" name="name" class="form-control rounded-pill" placeholder="e.g. Titanium Natural, Midnight Blue" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Color Picker / Hex Code *</label>
                    <div class="d-flex gap-2">
                        <input type="color" id="picker" class="form-control form-control-color rounded-3" value="#4f46e5" onchange="document.getElementById('code_input').value = this.value">
                        <input type="text" name="code" id="code_input" class="form-control rounded-pill fw-bold" value="#4f46e5" placeholder="#4f46e5" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Save Color Swatch</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3">Available Color Swatches</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th>Swatch</th>
                            <th>Color Name</th>
                            <th>Hex Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($colors as $color)
                            <tr>
                                <td>
                                    <span class="d-inline-block rounded-circle border shadow-sm" style="width: 28px; height: 28px; background-color: {{ $color->code }};"></span>
                                </td>
                                <td class="fw-bold text-dark">{{ $color->name }}</td>
                                <td><code class="fw-bold text-primary">{{ $color->code }}</code></td>
                                <td>
                                    <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" onsubmit="return confirm('Delete color?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $colors->links() }}
        </div>
    </div>
</div>
@endsection
