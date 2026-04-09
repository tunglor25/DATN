@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-decoration-none" style="color: #667eea;">Sản phẩm</a></li>
                    <li class="breadcrumb-item active">Thùng rác</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0" style="color: #1a202c;">
                <i class="fas fa-trash-alt me-2" style="color: #e53e3e;"></i>Thùng rác sản phẩm
            </h4>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-3">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            @if($trashedProducts->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-trash" style="font-size: 3.5rem; color: #cbd5e0;"></i>
                    </div>
                    <h5 class="text-muted fw-semibold">Thùng rác trống</h5>
                    <p class="text-muted small mb-0">Không có sản phẩm nào trong thùng rác</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 280px;">SẢN PHẨM</th>
                                <th>GIÁ</th>
                                <th>DANH MỤC</th>
                                <th>THƯƠNG HIỆU</th>
                                <th style="width: 120px;" class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trashedProducts as $product)
                                <tr style="opacity: 0.85;">
                                    <td>
                                        <span class="text-muted fw-medium">{{ ($trashedProducts->currentPage() - 1) * $trashedProducts->perPage() + $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                @if($product->product_image)
                                                    <img src="{{ asset('storage/' . $product->product_image) }}" 
                                                         alt="{{ $product->name }}" 
                                                         style="width: 48px; height: 48px; object-fit: cover; border-radius: 10px; border: 2px solid #f0f4f8; filter: grayscale(30%);">
                                                @else
                                                    <div style="width: 48px; height: 48px; border-radius: 10px; background: #f0f4f8; display: flex; align-items: center; justify-content: center; color: #cbd5e0;">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="color: #4a5568; font-size: 0.875rem;">{{ $product->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span style="color: #718096;">{{ number_format($product->price) }}₫</span></td>
                                    <td><span class="text-muted">{{ $product->category->name ?? '—' }}</span></td>
                                    <td><span class="text-muted">{{ $product->brand->name ?? '—' }}</span></td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="d-inline restore-form" data-name="{{ $product->name }}">
                                            @csrf
                                            <button type="button" class="btn btn-sm btn-success restore-btn" data-bs-toggle="tooltip" title="Khôi phục">
                                                <i class="fas fa-undo me-1"></i> Khôi phục
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Pagination --}}
    @if ($trashedProducts->count() > 0)
        <div class="d-flex justify-content-between align-items-center px-3 py-3">
            <div class="text-muted small">
                Hiển thị {{ $trashedProducts->firstItem() }}–{{ $trashedProducts->lastItem() }} / {{ $trashedProducts->total() }} sản phẩm
            </div>
            <div>
                {!! $trashedProducts->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Restore confirmation
        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.restore-form');
                const productName = form.getAttribute('data-name');

                Swal.fire({
                    title: 'Khôi phục sản phẩm?',
                    html: `Bạn muốn khôi phục <strong>"${productName}"</strong>?`,
                    icon: 'question',
                    iconColor: '#38a169',
                    showCancelButton: true,
                    confirmButtonColor: '#38a169',
                    cancelButtonColor: '#718096',
                    confirmButtonText: '<i class="fas fa-undo me-1"></i> Khôi phục',
                    cancelButtonText: 'Hủy',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
