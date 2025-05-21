{{-- @extends('layouts/layoutMaster')

@section('title', 'Order Details')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
@vite([
  // 'resources/assets/js/app-ecommerce-order-details.js',
  // 'resources/assets/js/modal-add-new-address.js',
  // 'resources/assets/js/modal-edit-user.js'
])
@endsection

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
  <div class="d-flex flex-column justify-content-center">
    <div class="mb-1">
      <span class="h5">{{ __('Order') }} #{{ $order->id }}</span>
      <span class="badge bg-label-{{ $order->payment_status === 'paid' ? 'success' : 'danger' }} me-1 ms-2">
        {{ ucfirst($order->payment_status) }}
      </span>
      <span class="badge bg-label-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'refunded' ? 'info' : 'danger')) }}">
        {{ ucfirst($order->status) }}
      </span>
    </div>
    <p class="mb-0">{{ $order->created_at->format('M d, Y, h:i A') }}</p>
  </div>
  <div class="d-flex align-content-center flex-wrap gap-2">
    <button class="btn btn-label-danger delete-order">{{ __('Delete Order') }}</button>
  </div>
</div>

<!-- Order Details Table -->

<div class="row">
  <div class="col-12 col-lg-8">
    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0">{{ __('Order details') }}</h5>
        <h6 class="m-0"><a href="javascript:void(0)">{{ __('Edit') }}</a></h6>
      </div>
      <div class="card-datatable table-responsive">
        <table class="datatables-order-details table border-top">
          <thead>
            <tr>
              <th></th>
              <th class="w-50">{{ __('Products') }}</th>
              <th class="w-25">{{ __('Price') }}</th>
              <th class="w-25">{{ __('Qty') }}</th>
              <th>{{ __('Total') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->orderItems as $item)
              <tr>
                <td></td>
                <td>
                  <div class="d-flex justify-content-start align-items-center text-nowrap">
                    <div class="avatar-wrapper">
                      <div class="avatar avatar-sm me-3">
                        <img src="{{ asset($item->product->image) }}" alt="product-{{ $item->product->name }}" class="rounded-2">
                      </div>
                    </div>
                    <div class="d-flex flex-column">
                      <h6 class="text-heading mb-0">{{ $item->product->name }}</h6>
                      <small>{{ $item->product?->brand ?? "RAM"}}</small>
                    </div>
                  </div>
                </td>
                <td>${{ number_format($item->product_price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->total_price, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <div class="d-flex justify-content-end align-items-center m-6 mb-2">
          <div class="order-calculations">
            <div class="d-flex justify-content-start mb-2">
              <span class="w-px-100 text-heading">{{ __('Subtotal') }}:</span>
              <h6 class="mb-0">${{ number_format($order->subtotal, 2) }}</h6>
            </div>
            <div class="d-flex justify-content-start mb-2">
              <span class="w-px-100 text-heading">{{ __('Discount') }}:</span>
              <h6 class="mb-0">${{ number_format($order->discount_amount, 2) }}</h6>
            </div>
            <div class="d-flex justify-content-start mb-2">
              <span class="w-px-100 text-heading">{{ __('Delivery') }}:</span>
              <h6 class="mb-0">${{ number_format($order->delivery_amount, 2) }}</h6>
            </div>
            <div class="d-flex justify-content-start">
              <h6 class="w-px-100 mb-0">{{ __('Total') }}:</h6>
              <h6 class="mb-0">${{ number_format($order->total_amount, 2) }}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-6">
      <div class="card-header">
        <h5 class="card-title m-0">{{ __('Shipping activity') }}</h5>
      </div>
      <div class="card-body pt-1">
        <ul class="timeline pb-0 mb-0">
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point timeline-point-primary"></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">{{ __('Order was placed (Order ID: #') }}{{ $order->id }})</h6>
                <small class="text-muted">{{ $order->created_at->format('l h:i A') }}</small>
              </div>
              <p class="mt-3">{{ __('Your order has been placed successfully') }}</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point timeline-point-primary"></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">{{ __('Pick-up') }}</h6>
                <small class="text-muted">{{ __('Wednesday 11:29 AM') }}</small>
              </div>
              <p class="mt-3 mb-3">{{ __('Pick-up scheduled with courier') }}</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-left-dashed">
            <span class="timeline-point timeline-point-secondary"></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">{{ __('Dispatched') }}</h6>
                <small class="text-muted">{{ __('Thursday 11:29 AM') }}</small>
              </div>
              <p class="mt-3 mb-3">{{ __('Item has been picked up by courier') }}</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-left-dashed">
            <span class="timeline-point timeline-point-secondary"></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">{{ __('Package arrived') }}</h6>
                <small class="text-muted">{{ __('Saturday 15:20 AM') }}</small>
              </div>
              <p class="mt-3 mb-3">{{ __('Package arrived at an Amazon facility, NY') }}</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-left-dashed">
            <span class="timeline-point timeline-point-secondary"></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">{{ __('Dispatched for delivery') }}</h6>
                <small class="text-muted">{{ __('Today 14:12 PM') }}</small>
              </div>
              <p class="mt-3 mb-3">{{ __('Package has left an Amazon facility, NY') }}</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-transparent pb-0">
            <span class="timeline-point timeline-point-secondary"></span>
            <div class="timeline-event pb-0">
              <div class="timeline-header">
                <h6 class="mb-0">{{ __('Delivery') }}</h6>
              </div>
              <p class="mt-1 mb-0">{{ __('Package will be delivered by tomorrow') }}</p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  

  <div class="col-12 col-lg-4">
    <!-- Customer Details Card -->
    <div class="card mb-6">
      <div class="card-header">
        <h5 class="card-title m-0">{{ __('Customer details') }}</h5>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-start align-items-center mb-6">
          <div class="avatar me-3">
            <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle">
          </div>
          <div class="d-flex flex-column">
            <a href="{{ url('app/user/view/account') }}" class="text-body text-nowrap">
              <h6 class="mb-0">{{ $order->user->name }}</h6>
            </a>
            <span>{{ __('Customer ID: #') }}{{ $order->user->id }}</span>
          </div>
        </div>
        <div class="d-flex justify-content-start align-items-center mb-6">
          <span class="avatar rounded-circle bg-label-success me-3 d-flex align-items-center justify-content-center">
            <i class='ti ti-shopping-cart ti-lg'></i>
          </span>
          <h6 class="text-nowrap mb-0">{{ $order->user->orders->count() }} {{ __('Orders') }}</h6>
        </div>
        <div class="d-flex justify-content-between">
          <h6 class="mb-1">{{ __('Contact info') }}</h6>
          <h6 class="mb-1"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editUser">{{ __('Edit') }}</a></h6>
        </div>
        <p class="mb-1">{{ __('Email:') }} {{ $order->user->email }}</p>
        <p class="mb-0">{{ __('Mobile:') }} {{ $order->user->phone ?? 'N/A' }}</p>
      </div>
    </div>

    <!-- Shipping Address Card -->
    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title m-0">{{ __('Shipping address') }}</h5>
        <h6 class="m-0"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">{{ __('Edit') }}</a></h6>
      </div>
      <div class="card-body">
        <p class="mb-0">{{ $order->shipping_address }}</p>
      </div>
    </div>

    <!-- Billing Address Card -->
    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title m-0">{{ __('Billing address') }}</h5>
        <h6 class="m-0"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">{{ __('Edit') }}</a></h6>
      </div>
      <div class="card-body">
        <p class="mb-6">{{ $order->shipping_address }}</p>
        <h5 class="mb-1">{{ $order->payment_method }}</h5>
        <p class="mb-0">{{ __('Card Number:') }} ******{{ substr($order->payment_method, -4) }}</p>
      </div>
    </div>
  </div>


</div>

<!-- Modals -->
@include('_partials/_modals/modal-edit-user')
@include('_partials/_modals/modal-add-new-address')

@endsection --}}


@php
  use App\Enums\Order\OrderStatusEnum; // استيراد الـ Enum في أعلى الملف
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Order Details'))

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
@vite([
  // 'resources/assets/js/app-ecommerce-order-details.js', // قم بإلغاء التعليق إذا كنت ستستخدم JS مخصص
  // 'resources/assets/js/modal-add-new-address.js',
  // 'resources/assets/js/modal-edit-user.js'
])
@endsection

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
  <div class="d-flex flex-column justify-content-center">
    <div class="mb-1">
      <span class="h5">{{ __('Order') }} #{{ $order->id }}</span>
      @php
        $paymentStatusClass = 'secondary'; // افتراضي
        if ($order->payment_status === 'paid' || $order->payment_status === 'مدفوع') {
            $paymentStatusClass = 'success';
        } elseif (in_array($order->payment_status, ['pending', 'قيد الانتظار'])) {
            $paymentStatusClass = 'warning';
        } elseif (in_array($order->payment_status, ['failed', 'فشل الدفع', 'cancelled', 'ملغي'])) {
            $paymentStatusClass = 'danger';
        } elseif (in_array($order->payment_status, ['refunded', 'مسترجع'])) {
            $paymentStatusClass = 'info';
        }
      @endphp
      <span class="badge bg-label-{{ $paymentStatusClass }} ms-2">
        {{ __(ucfirst(str_replace('_', ' ', $order->payment_status))) }}
      </span>

      @php
        $orderStatusEnum = null;
        try {
            $orderStatusEnum = OrderStatusEnum::from($order->status);
        } catch (\ValueError $e) {
            // Handle cases where status might not be a valid enum value (e.g. old data)
        }
        $orderStatusClass = 'secondary'; // افتراضي
        if ($orderStatusEnum) {
            switch($orderStatusEnum) {
                case OrderStatusEnum::DELIVERED:
                    $orderStatusClass = 'success';
                    break;
                case OrderStatusEnum::PENDING:
                case OrderStatusEnum::PROCESSING:
                    $orderStatusClass = 'warning';
                    break;
                case OrderStatusEnum::CANCELLED:
                case OrderStatusEnum::FAILED:
                    $orderStatusClass = 'danger';
                    break;
                case OrderStatusEnum::REFUNDED:
                case OrderStatusEnum::SHIPPED:
                case OrderStatusEnum::ON_WAY:
                    $orderStatusClass = 'info';
                    break;
            }
        }
      @endphp
      <span class="badge bg-label-{{ $orderStatusClass }} ms-1">
         {{ $orderStatusEnum ? __($orderStatusEnum->description()) : __(ucfirst($order->status)) }}
      </span>
    </div>
    <p class="mb-0">{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d M, Y, h:i A') }}</p>
  </div>
  <div class="d-flex align-content-center flex-wrap gap-2">
    {{-- أزرار إجراءات إضافية يمكن وضعها هنا، مثل تغيير حالة الطلب، تعيين سائق، الخ. --}}
    <button class="btn btn-label-danger delete-order">{{ __('Delete Order') }}</button>
  </div>
</div>

<!-- Order Details Table -->

<div class="row">
  <div class="col-12 col-lg-8">
    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0">{{ __('Order details') }}</h5>
        {{-- <h6 class="m-0"><a href="javascript:void(0)">{{ __('Edit') }}</a></h6> --}} {{-- زر التعديل يمكن تفعيله لاحقاً --}}
      </div>
      <div class="card-datatable table-responsive">
        <table class="datatables-order-details table border-top">
          <thead>
            <tr>
              <th></th> {{-- For responsive control or checkboxes if needed --}}
              <th class="w-50">{{ __('Products') }}</th>
              <th class="w-25">{{ __('Price') }}</th>
              <th class="w-25">{{ __('Qty') }}</th>
              <th>{{ __('Total') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($order->orderItems as $item)
              <tr>
                <td></td>
                <td>
                  <div class="d-flex justify-content-start align-items-center text-nowrap">
                    @if($item->product && $item->product->image)
                    <div class="avatar-wrapper">
                      <div class="avatar avatar-sm me-3">
                        {{-- افترض أن الصور مخزنة في public/storage --}}
                        <img src="{{ $item->product->image }}" alt="{{ $item->product_name }}" class="rounded-2">
                      </div>
                    </div>
                    @else
                     <div class="avatar-wrapper">
                      <div class="avatar avatar-sm me-3 bg-label-secondary">
                        <span class="avatar-initial rounded-2"><i class="ti ti-photo ti-sm"></i></span>
                      </div>
                    </div>
                    @endif
                    <div class="d-flex flex-column">
                      <h6 class="text-heading mb-0">{{ $item->product_name }}</h6>
                      {{-- <small>{{ $item->product?->category?->name ?? __('N/A') }}</small> --}} {{-- يمكنك عرض اسم الصنف إذا أردت --}}
                    </div>
                  </div>
                </td>
                <td>${{ number_format($item->product_price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->total_price, 2) }}</td> {{-- يعتمد على accessor في OrderItem --}}
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4">{{ __('No items in this order.') }}</td>
              </tr>
            @endforelse
          </tbody>
        </table>
        <div class="d-flex justify-content-end align-items-center m-6 mb-2">
          <div class="order-calculations">
            <div class="d-flex justify-content-between mb-2"> {{-- Changed to justify-content-between --}}
              <span class="w-px-100 text-heading">{{ __('Subtotal') }}:</span>
              <h6 class="mb-0">${{ number_format($order->subtotal, 2) }}</h6>
            </div>
            @if($order->discount_amount > 0)
            <div class="d-flex justify-content-between mb-2">
              <span class="w-px-100 text-heading">{{ __('Discount') }}:</span>
              <h6 class="mb-0 text-danger">-${{ number_format($order->discount_amount, 2) }}</h6>
            </div>
            @endif
            <div class="d-flex justify-content-between mb-2">
              <span class="w-px-100 text-heading">{{ __('Delivery Fee') }}:</span> {{-- Changed to Delivery Fee --}}
              <h6 class="mb-0">${{ number_format($order->delivery_amount, 2) }}</h6>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <h6 class="w-px-100 mb-0">{{ __('Total') }}:</h6>
              <h6 class="mb-0">${{ number_format($order->total_amount, 2) }}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0">{{ __('Shipping activity') }}</h5>
        @if($order->driver)
            <h6 class="m-0">{{ __('Assigned Driver:') }} <a href="#">{{ $order->driver->name }}</a> ({{$order->driver->phone}})</h6>
        @elseif(in_array(OrderStatusEnum::tryFrom($order->status), [OrderStatusEnum::PENDING, OrderStatusEnum::PROCESSING]))
            <button class="btn btn-sm btn-outline-primary">{{__('Assign Driver')}}</button>
        @endif
      </div>
      <div class="card-body pt-1">
        @if($order->activity && $order->activity->count() > 0)
          <ul class="timeline pb-0 mb-0">
            @foreach($order->activity->sortBy('created_at') as $activity_item)
            <li class="timeline-item timeline-item-transparent {{ $loop->last ? 'border-transparent pb-0' : 'border-left-dashed' }}">
              <span class="timeline-point timeline-point-{{ $loop->first ? 'primary' : 'secondary' }}"></span>
              <div class="timeline-event {{ $loop->last ? 'pb-0' : '' }}">
                <div class="timeline-header">
                  {{-- افترض أن activity_item->description يحتوي على وصف الحدث --}}
                  <h6 class="mb-0">{{ __($activity_item->description) }}</h6>
                  <small class="text-muted">{{ \Carbon\Carbon::parse($activity_item->created_at)->translatedFormat('l h:i A') }}</small>
                </div>
                @if(isset($activity_item->details)) {{-- إذا كان هناك تفاصيل إضافية --}}
                <p class="mt-1 {{ $loop->last ? 'mb-0' : 'mb-3' }}">{{ $activity_item->details }}</p>
                @endif
              </div>
            </li>
            @endforeach
          </ul>
        @else
          <ul class="timeline pb-0 mb-0">
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0">{{ __('Order was placed (Order ID: #') }}{{ $order->id }})</h6>
                  <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('l h:i A') }}</small>
                </div>
                <p class="mt-2">{{ __('Your order has been placed successfully') }}</p>
              </div>
            </li>
             @if($order->status !== OrderStatusEnum::PENDING->value)
                <li class="timeline-item timeline-item-transparent {{ $order->status === OrderStatusEnum::DELIVERED->value ? 'border-transparent pb-0' : 'border-left-dashed' }}">
                    <span class="timeline-point timeline-point-secondary"></span>
                    <div class="timeline-event {{ $order->status === OrderStatusEnum::DELIVERED->value ? 'pb-0' : '' }}">
                        <div class="timeline-header">
                            <h6 class="mb-0">
                                @php
                                    $currentStatusEnum = OrderStatusEnum::tryFrom($order->status);
                                @endphp
                                {{ $currentStatusEnum ? __($currentStatusEnum->description()) : __(ucfirst($order->status)) }}
                            </h6>
                            @if($order->updated_at != $order->created_at)
                                <small class="text-muted">{{ \Carbon\Carbon::parse($order->updated_at)->translatedFormat('l h:i A') }}</small>
                            @endif
                        </div>
                         @if($order->status === OrderStatusEnum::DELIVERED->value)
                            <p class="mt-1 mb-0">{{ __('The order has been successfully delivered.') }}</p>
                         @elseif($order->status === OrderStatusEnum::CANCELLED->value)
                            <p class="mt-1 mb-0">{{ __('The order has been cancelled.') }}</p>
                         @endif
                    </div>
                </li>
            @endif
          </ul>
        @endif
      </div>
    </div>
  </div>


  <div class="col-12 col-lg-4">
    <!-- Customer Details Card -->
    <div class="card mb-6">
      <div class="card-header">
        <h5 class="card-title m-0">{{ __('Customer details') }}</h5>
      </div>
      <div class="card-body">
        @if($order->user)
        <div class="d-flex justify-content-start align-items-center mb-4">
           <div class="avatar-wrapper">
                <div class="avatar avatar-sm me-3">
                  @php
                    $states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                    $state = $states[array_rand($states)];
                    $initials = Illuminate\Support\Str::upper(Illuminate\Support\Str::substr($order->user->name, 0, 1));
                  @endphp
                  <span class="avatar-initial rounded-circle bg-label-{{ $state }}">{{ $initials }}</span>
                </div>
              </div>
        
          <div class="d-flex flex-column">
            <a href="{{ url('app/user/view', $order->user->id) }}" class="text-body text-nowrap"> {{-- رابط لعرض تفاصيل المستخدم --}}
              <h6 class="mb-0">{{ $order->user->name }}</h6>
            </a>
            <small class="text-muted">{{ __('Customer ID: #') }}{{ $order->user->id }}</small>
          </div>
        </div>
        <div class="d-flex justify-content-start align-items-center mb-4">
          <span class="avatar rounded-circle bg-label-success me-3 d-flex align-items-center justify-content-center">
            <i class='ti ti-shopping-cart ti-sm'></i>
          </span>
          <h6 class="text-nowrap mb-0">{{ $order->user->orders->count() }} {{ __('Orders') }}</h6>
        </div>
        <div class="d-flex justify-content-between">
          <h6 class="mb-1">{{ __('Contact Info') }}</h6> {{-- Changed to Contact Info --}}
          {{-- <h6 class="mb-1"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editUser">{{ __('Edit') }}</a></h6> --}}
        </div>
        <p class="mb-1"><span class="text-heading">{{ __('Email:') }}</span> {{ $order->user->email }}</p>
        <p class="mb-0"><span class="text-heading">{{ __('Mobile:') }}</span> {{ $order->user->phone ?? __('N/A') }}</p>
        @else
        <p class="text-muted">{{ __('Customer details not available.') }}</p>
        @endif
      </div>
    </div>

    <!-- Shipping Address Card -->
    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title m-0">{{ __('Shipping address') }}</h5>
        {{-- <h6 class="m-0"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">{{ __('Edit') }}</a></h6> --}}
      </div>
      <div class="card-body">
        @if($order->shipping_address)
            <p class="mb-0">{{ $order->shipping_address }}</p>
        @else
            <p class="text-muted mb-0">{{__('Shipping address not provided.')}}</p>
        @endif
      </div>
    </div>

    <!-- Billing & Payment Card -->
    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title m-0">{{ __('Billing & Payment') }}</h5>
        {{-- <h6 class="m-0"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">{{ __('Edit') }}</a></h6> --}}
      </div>
      <div class="card-body">
         @if($order->shipping_address) {{-- يمكن استخدام عنوان الفوترة إذا كان مختلفًا، وإلا افترض أنه نفس عنوان الشحن --}}
            <h6 class="mb-2">{{__('Billing Address:')}}</h6>
            <p class="mb-3">{{ $order->shipping_address }}</p>
        @endif
        <h6 class="mb-1">{{__('Payment Method:')}} <span class="text-body fw-normal">{{ __(ucfirst(str_replace('_', ' ', $order->payment_method))) }}</span></h6>
        @if($order->payment_method === 'credit_card' || $order->payment_method === 'بطاقة ائتمانية')
            {{-- يمكنك هنا عرض تفاصيل جزئية للبطاقة إذا كانت مخزنة بشكل آمن ومشفر --}}
            {{-- <p class="mb-0">{{ __('Card Number:') }} ******{{ substr($order->some_stored_card_info, -4) }}</p> --}}
            <p class="mb-0 text-muted">{{__('Card details are securely processed.')}}</p>
        @endif
        <h6 class="mb-0 mt-2">{{__('Payment Status:')}} <span class="text-body fw-normal">{{ __(ucfirst(str_replace('_', ' ', $order->payment_status))) }}</span></h6>

      </div>
    </div>
  </div>
</div>
@endsection