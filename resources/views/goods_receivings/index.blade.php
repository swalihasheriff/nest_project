@extends('layouts.app')
@section('title', 'Goods Receiving')
@section('content')

    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Goods Receiving</h4>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-3">

                <div class="table-responsive">
                    <table id="goodsReceivingTable" class="table table-bordered table-striped align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>Sl No</th>
                                <th>Supplier</th>
                                <th>Received On</th>
                                <th>Received By</th>
                                <th>Status</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No records found
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

    <div class="modal fade" id="editReceiveModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="receiveForm">
                @csrf

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Update Goods Receiving</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="order_id">
                        <input type="hidden" id="receiving_id" name="receiving_id">
                        <input type="hidden" name="order_id" id="order_id">
                        <input type="hidden" name="supplier_id" id="supplier_id">
                        <div class="mb-3">
                            <label class="form-label">Received By</label>
                            <input type="text" name="received_by" id="received_by" class="form-control"
                                placeholder="Enter receiver name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Received On</label>
                            <input type="date" name="received_on" id="received_on" class="form-control"
                                max="{{ date('Y-m-d') }}">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Save
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const TABLE_ID = '#goodsReceivingTable';
        const INDEX_URL = "{{ route('goods.receiving.index') }}";
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="{{ asset('js/goods_receivings/datatable.js') }}"></script>
    <script src="{{ asset('js/goods_receivings/edit.js') }}"></script>

@endpush