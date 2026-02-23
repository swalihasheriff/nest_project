@extends('layouts.app')
@section('title', 'Nest | View Product')

@section('content')
    <div class="container-fluid py-3">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">View Product</h4>

            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <!-- Card -->
        <div class="card shadow-sm">
            <div class="card-body p-3">

                <form id="productForm">

                    <!-- Row 1 -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-8">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" value="{{ $product->description }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Supplier</label>
                            <select class="form-select" disabled>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-8">
                            <label class="form-label">CTN Barcode</label>
                            <input type="text" class="form-control" value="{{ $product->ctn_barcode }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">UPC</label>
                            <input type="text" class="form-control" value="{{ $product->upc }}" disabled>
                        </div>
                    </div>

                    <!-- Row 3 -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label class="form-label">Product Barcode 1</label>
                            <input type="text" class="form-control" value="{{ $product->product_barcode1 }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Product Barcode 2</label>
                            <input type="text" class="form-control" value="{{ $product->product_barcode2 }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Product Barcode 3</label>
                            <input type="text" class="form-control" value="{{ $product->product_barcode3 }}" disabled>
                        </div>
                    </div>

                    <!-- Row 4 -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-8">
                            <label class="form-label">Cost Price (excl GST)</label>
                            <input type="number" class="form-control" value="{{ $product->ctn_cost_price }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">GST (%)</label>
                            <input type="number" class="form-control" value="{{ $product->gst }}" disabled>
                        </div>
                    </div>

                    <!-- Row 5 -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-2">
                            <label class="form-label">Sell Price</label>
                            <input type="number" class="form-control" value="{{ $product->ctn_sell_price }}" disabled>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Sell Price 2</label>
                            <input type="number" class="form-control" value="{{ $product->sell_price2 }}" disabled>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Sell Price 3</label>
                            <input type="number" class="form-control" value="{{ $product->sell_price3 }}" disabled>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Sell Price 4</label>
                            <input type="number" class="form-control" value="{{ $product->sell_price4 }}" disabled>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Sell Price 5</label>
                            <input type="number" class="form-control" value="{{ $product->sell_price5 }}" disabled>
                        </div>
                    </div>

                    <!-- Row 6 -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-3">
                            <label class="form-label">Stock on Hand</label>
                            <input type="number" class="form-control" value="{{ $product->stock_on_hand }}" disabled>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Minimum Threshold</label>
                            <input type="number" class="form-control" value="{{ $product->minimum_threshold }}" disabled>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" value="{{ $product->location }}" disabled>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Reorder</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" {{ $product->reorder ? 'checked' : '' }}
                                    disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Row 7 -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label class="form-label">Weight</label>
                            <input type="text" class="form-control" value="{{ $product->weight }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Length</label>
                            <input type="text" class="form-control" value="{{ $product->length }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">UOM</label>
                            <input type="text" class="form-control" value="{{ $product->uom }}" disabled>
                        </div>
                    </div>

                    <!-- Row 8 -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Product Code</label>
                            <input type="text" class="form-control" value="{{ $product->product_code }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Supplier Code</label>
                            <input type="text" class="form-control" value="{{ $product->supplier_code }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Shelf Capacity</label>
                            <input type="number" class="form-control" value="{{ $product->shelf_capacity }}" disabled>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="mt-4">
                    <h5 class="form label mb-3">Product Images</h5>
                    @php
                      $images = [];

                      if (is_array($product->images)) {
                            $images = $product->images;
                    } elseif (is_string($product->images)) {
                            $images = json_decode($product->images, true) ?? [];
                    }
                    @endphp

                    @if(count($images) > 0)
                       <div class="row g-3">
                           @foreach($images as $image)
                            @if(is_string($image))
                               <div class="col-md-3">
                                 <img src="{{ asset('storage/' . $image) }}"
                                      class="img-fluid rounded border"
                                       alt="Product Image">
                                </div>
                            @endif
                           @endforeach
                        </div>
                    @else
                  <p class="text-muted">No images uploaded for this product.</p>
                    @endif
                    </div>
                    

                </form>

            </div>
        </div>
    </div>
@endsection