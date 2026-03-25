@extends('layouts.app')
@section('title', 'Nest | New Account')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">New Account</h4>

            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <form id="accountForm" method="POST" action="{{ route('accounts.store') }}">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" placeholder="Company Name">
                        </div>

                       
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Address">
                        </div>

                       
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ABN</label>
                            <input type="text" name="abn" class="form-control" placeholder="ABN">
                        </div>

                       
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Name</label>
                            <input type="text" name="contact_name" class="form-control" placeholder="Contact Name">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" placeholder="Contact Number">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="Remarks"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Reference Number</label>
                            <input type="text" name="accnt_ref_number" class="form-control" placeholder="Reference Number">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="Username">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price Group <span class="text-danger">*</span></label>
                            <select name="price_group" class="form-select">
                                <option value="">Please select</option>
                                <option value="ctn">Carton Price</option>
                                <option value="p1">Sell Price 1</option>
                                <option value="p2">Sell Price 2</option>
                                <option value="p3">Sell Price 3</option>
                                <option value="p4">Sell Price 4</option>
                                <option value="p5">Sell Price 5</option>
                            </select>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Save Account
                            </button>

                            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>

                </form>

            </div>
        </div>

    </div>
@endsection

@push('scripts')

    <script>
        const ACCOUNT_STORE_URL = "{{ route('accounts.store') }}";
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/accounts/add.js') }}"></script>

@endpush