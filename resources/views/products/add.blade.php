@extends('layouts.app')
@section('title', 'Nest | Add Product')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Add Product</h4>

            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-3">
                <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Row 1 -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-8">
                            <label class="form-label">
                                Description <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="description" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Supplier <span class="text-danger">*</span>
                            </label>
                            <select name="supplier_id" class="form-select">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-8">
                            <label class="form-label">
                                CTN Barcode <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="ctn_barcode" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                UPC <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="upc" class="form-control">
                        </div>
                    </div>

                    <!-- Row 3 -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label class="form-label">
                                Product Barcode 1 <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="product_barcode1" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Product Barcode 2</label>
                            <input type="text" name="product_barcode2" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Product Barcode 3</label>
                            <input type="text" name="product_barcode3" class="form-control">
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-2">
                        <div class="col-md-8">
                            <label class="form-label">
                                Cost Price (excl gst) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" name="ctn_cost_price" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                GST <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" name="gst" class="form-control">
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-2">
                        <div class="col-md-2">
                            <label class="form-label">
                                Sell Price <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" name="ctn_sell_price" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Sell Price 2</label>
                            <input type="number" step="0.01" name="sell_price2" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Sell Price 3</label>
                            <input type="number" step="0.01" name="sell_price3" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Sell Price 4</label>
                            <input type="number" step="0.01" name="sell_price4" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Sell Price 5</label>
                            <input type="number" step="0.01" name="sell_price5" class="form-control">
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-2">
                        <div class="col-md-3">
                            <label class="form-label">
                                Stock on Hand <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="stock_on_hand" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                Minimum Threshold <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="minimum_threshold" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                Location <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="location" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Reorder</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="reorder" value="1">
                            </div>
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label class="form-label">Weight</label>
                            <input type="text" name="weight" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Length</label>
                            <input type="text" name="length" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                UOM <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="uom" class="form-control">
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Product Code</label>
                            <input type="text" name="product_code" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Supplier Code</label>
                            <input type="text" name="supplier_code" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Shelf Capacity</label>
                            <input type="number" name="shelf_capacity" class="form-control">
                        </div>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label">Product Images</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>

                    
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const PRODUCT_STORE_URL = "{{ route('products.store') }}";
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/products/add.js') }}"></script>

@endpush