@extends('layouts.app')
@section('title', 'Nest | Edit Account')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Edit Account</h4>

            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <form id="accountForm" method="POST" action="{{ route('accounts.update', $account->id) }}">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name *</label>
                            <input type="text" name="company_name" class="form-control"
                                value="{{ $account->company_name }}">
                        </div>

                        <input type="hidden" id="edit_account_id" value="{{ $account->id }}">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $account->address }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ABN</label>
                            <input type="text" name="abn" class="form-control" value="{{ $account->abn }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" value="{{ $account->email }}" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Name</label>
                            <input type="text" name="contact_name" class="form-control"
                                value="{{ $account->contact_name }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control"
                                value="{{ $account->contact_number }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2">{{ $account->remarks }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Reference Number</label>
                            <input type="text" name="accnt_ref_number" class="form-control"
                                value="{{ $account->accnt_ref_number }}">
                        </div>
 
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username *</label>
                            <input type="text" class="form-control" value="{{ $account->username }}" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Leave blank to keep current password">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price Group *</label>
                            <select name="price_group" class="form-select">
                                <option value="">Select</option>
                                <option value="ctn" {{ $account->price_group == 'ctn' ? 'selected' : '' }}>Carton Price
                                </option>
                                <option value="p1" {{ $account->price_group == 'p1' ? 'selected' : '' }}>Sell Price 1</option>
                                <option value="p2" {{ $account->price_group == 'p2' ? 'selected' : '' }}>Sell Price 2</option>
                                <option value="p3" {{ $account->price_group == 'p3' ? 'selected' : '' }}>Sell Price 3</option>
                                <option value="p4" {{ $account->price_group == 'p4' ? 'selected' : '' }}>Sell Price 4</option>
                                <option value="p5" {{ $account->price_group == 'p5' ? 'selected' : '' }}>Sell Price 5</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $account->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $account->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Update Account
                            </button>

                            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const ACCOUNT_UPDATE_URL = "{{ route('accounts.update', $account->id) }}";
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/accounts/edit.js') }}"></script>

@endpush