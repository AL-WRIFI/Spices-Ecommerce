@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product Update - Apps')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/quill/typography.scss',
  'resources/assets/vendor/libs/quill/katex.scss',
  'resources/assets/vendor/libs/quill/editor.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/dropzone/dropzone.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
  'resources/assets/vendor/libs/tagify/tagify.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/quill/katex.js',
  'resources/assets/vendor/libs/quill/quill.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/dropzone/dropzone.js',
  'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/tagify/tagify.js'
])
@endsection

@section('content')
<div class="app-ecommerce">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
    <div class="d-flex flex-column justify-content-center">
      <h4 class="mb-1">Update Product</h4>
      <p class="mb-0">Update product details</p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
      <div class="d-flex gap-4">
        <button class="btn btn-label-secondary">Discard</button>
        <button class="btn btn-label-primary">Save draft</button>
      </div>
      <button type="submit" form="product-form" class="btn btn-primary">Update Product</button>
    </div>
  </div>
  <form id="product-form" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="image_path" id="image-path" value="{{ $product->image_path }}"> 

    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card mb-6">
          <div class="card-header">
            <h5 class="card-tile mb-0">Product information</h5>
          </div>
          <div class="card-body">
            <div class="mb-6">
              <label class="form-label" for="ecommerce-product-name">Name</label>
              <input type="text" class="form-control" id="ecommerce-product-name" placeholder="Product title" name="name" value="{{ $product->name }}" aria-label="Product title" required>
            </div>
            <div class="row mb-6">
              <div class="col">
                <label class="form-label" for="ecommerce-product-price">Price</label>
                <input type="number" class="form-control" id="ecommerce-product-price" placeholder="Price" name="price" value="{{ $product->price }}" aria-label="Product price" required>
              </div>
              <div class="col">
                <label class="form-label" for="ecommerce-product-sale-price">Sale Price</label>
                <input type="number" class="form-control" id="ecommerce-product-sale-price" placeholder="Sale Price" name="sale_price" value="{{ $product->sale_price }}" aria-label="Product sale price">
              </div>
            </div>
            <div class="mb-6">
              <label class="form-label" for="basic-default-upload-file">Image</label>
              <input type="file" class="form-control" name="image" id="basic-default-upload-file" accept="image/*"/>
              @if ($product->image_url)
                <div class="mt-2">
                  <img src="{{ $product->image_url }}" alt="Product Image" style="max-width: 200px; height: auto;">
                </div>
              @endif
            </div>
            <div class="mb-6">
              <label class="form-label" for="ecommerce-product-summary">Summary</label>
              <textarea class="form-control" id="ecommerce-product-summary" placeholder="Product summary" name="summary" aria-label="Product summary">{{ $product->summary }}</textarea>
            </div>
            <div class="mb-6">
              <label class="form-label" for="ecommerce-product-description">Description</label>
              <textarea class="form-control" id="ecommerce-product-description" placeholder="Product description" name="description" aria-label="Product description">{{ $product->description }}</textarea>
            </div>
            <div class="row mb-6">
              <div class="col">
                <label class="form-label" for="ecommerce-product-quantity">Quantity</label>
                <input type="number" class="form-control" id="ecommerce-product-quantity" placeholder="Quantity" name="quantity" value="{{ $product->quantity }}" aria-label="Product quantity" required>
              </div>
              <div class="col">
                <label class="form-label" for="ecommerce-product-stock">Stock</label>
                <select class="form-select" id="ecommerce-product-stock" name="stock" required>
                  <option value="1" {{ $product->stock == 1 ? 'selected' : '' }}>In Stock</option>
                  <option value="0" {{ $product->stock == 0 ? 'selected' : '' }}>Out of Stock</option>
                </select>
              </div>
            </div>
            <div class="mb-6">
              <label class="form-label" for="ecommerce-product-status">Status</label>
              <select class="form-select" id="ecommerce-product-status" name="status" required>
                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Published</option>
                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">
        <div class="card mb-6">
          <div class="card-header">
            <h5 class="card-title mb-0">Organize</h5>
          </div>
          <div class="card-body">

            <div class="mb-6">
              <label class="form-label" for="ecommerce-product-sub-category">Sub Category</label>
              <select class="form-select" id="ecommerce-product-sub-category" name="sub_category_id" required>
                @foreach($subCategories as $subCategory)
                  <option value="{{ $subCategory->id }}" {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-6">
              <label class="form-label" for="ecommerce-product-unit">Unit</label>
              <select class="form-select" id="ecommerce-product-unit" name="unit_id" required>
                @foreach($units as $unit )
                  <option value="{{ $unit->id }}" {{ $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="text-end mt-4">
      <button type="submit" class="btn btn-primary">Update Product</button>
    </div>
</form>
</div>

<script>
  'use strict';

  // Dropzone
  const previewTemplate = `<div class="dz-preview dz-file-preview">
    <div class="dz-details">
      <div class="dz-thumbnail">
        <img data-dz-thumbnail>
        <span class="dz-nopreview">No preview</span>
        <div class="dz-success-mark"></div>
        <div class="dz-error-mark"></div>
        <div class="dz-error-message"><span data-dz-errormessage></span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
        </div>
      </div>
      <div class="dz-filename" data-dz-name></div>
      <div class="dz-size" data-dz-size></div>
    </div>
  </div>`;

  const dropzoneBasic = document.querySelector('#dropzone-basic');
  if (dropzoneBasic) {
    const myDropzone = new Dropzone(dropzoneBasic, {
      url: "{{ route('products.update', $product->id) }}", // Route الخاص برفع الصور
      paramName: "image", // اسم الحقل الذي سيتم إرسال الصورة عبره
      maxFilesize: 5, // الحد الأقصى لحجم الملف
      acceptedFiles: '.jpg,.jpeg,.png,.gif', // أنواع الملفات المقبولة
      addRemoveLinks: true, // إظهار رابط لإزالة الملف
      maxFiles: 1, // الحد الأقصى لعدد الملفات
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // إضافة CSRF Token
      },
      init: function () {
        this.on("success", function (file, response) {
          console.log("File uploaded successfully:", response);
          // تخزين مسار الصورة في الحقل المخفي
          document.getElementById('image-path').value = response.path;
        });
        this.on("error", function (file, errorMessage) {
          console.error("Error uploading file:", errorMessage);
        });
      }
    });
  }
</script>

@endsection