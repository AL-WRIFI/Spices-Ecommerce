@php
  use App\Enums\Order\OrderStatusEnum;
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Order List'))

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
])
@endsection

@section('page-script')
@vite([])

<script>
  // تغيير الحالة
  const changeStatusModal = document.getElementById('changeStatusModal');
  const changeStatusForm = document.getElementById('changeStatusForm');
  changeStatusModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const orderId = button.getAttribute('data-order-id');
    const orderStatus = button.getAttribute('data-order-status');
    document.getElementById('modal_order_status').value = orderStatus;
    changeStatusForm.action = `/orders/changeStatus/${orderId}`;
  });

  // تعيين سائق
  const appointModal = document.getElementById('shareProject');
  appointModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const orderId = button.getAttribute('data-order-id');
    document.getElementById('appoint_order_id').value = orderId;
    document.getElementById('selectedDriverId').value = '';
    document.querySelector('.confirm-btn').disabled = true;
    document.querySelectorAll('.driver-item').forEach(d => d.classList.remove('selected'));
    document.getElementById('driverSearch').value = '';
    document.querySelectorAll('.driver-item').forEach(item => item.style.display = 'flex');
  });

  function selectDriver(item) {
    document.querySelectorAll('.driver-item').forEach(d => d.classList.remove('selected'));
    item.classList.add('selected');
    document.querySelector('.confirm-btn').disabled = false;
    document.getElementById('selectedDriverId').value = item.dataset.driverId;
  }

  document.getElementById('driverSearch').addEventListener('input', function (e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.driver-item').forEach(item => {
      const name = item.dataset.name.toLowerCase();
      const phone = item.dataset.phone;
      const vehicle = item.dataset.vehicle.toLowerCase();
      const matches = name.includes(searchTerm) || phone.includes(searchTerm) || vehicle.includes(searchTerm);
      item.style.display = matches ? 'flex' : 'none';
    });
  });
</script>
@endsection

@section('content')
<!-- الإحصائيات -->
<div class="card mb-6">
  <div class="card-widget-separator-wrapper">
    <div class="card-body card-widget-separator">
      <div class="row gy-4 gy-sm-1">
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
            <div>
              <h4 class="mb-0">{{ $pendingPaymentCount }}</h4>
              <p class="mb-0">{{ __('Pending Payment') }}</p>
            </div>
            <span class="avatar me-sm-6">
              <span class="avatar-initial bg-label-secondary rounded text-heading">
                <i class="ti ti-calendar-stats"></i>
              </span>
            </span>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
            <div>
              <h4 class="mb-0">{{ $completedOrdersCount }}</h4>
              <p class="mb-0">{{ __('Completed') }}</p>
            </div>
            <span class="avatar p-2 me-lg-6">
              <span class="avatar-initial bg-label-secondary rounded"><i class="ti ti-checks"></i></span>
            </span>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
            <div>
              <h4 class="mb-0">{{ $refundedOrdersCount }}</h4>
              <p class="mb-0">{{ __('Refunded') }}</p>
            </div>
            <span class="avatar p-2 me-sm-6">
              <span class="avatar-initial bg-label-secondary rounded"><i class="ti ti-wallet"></i></span>
            </span>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h4 class="mb-0">{{ $failedOrdersCount }}</h4>
              <p class="mb-0">{{ __('Failed') }}</p>
            </div>
            <span class="avatar p-2">
              <span class="avatar-initial bg-label-secondary rounded"><i class="ti ti-alert-octagon"></i></span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- جدول الطلبات -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatables-order table border-top">
      <thead>
        <tr>
          <th></th>
          <th>{{ __('Order ID') }}</th>
          <th>{{ __('Date') }}</th>
          <th>{{ __('Customer') }}</th>
          <th>{{ __('Subtotal') }}</th>
          <th>{{ __('Discount') }}</th>
          <th>{{ __('Coupon') }}</th>
          <th>{{ __('Delivery') }}</th>
          <th>{{ __('Total Amount') }}</th>
          <th>{{ __('Payment Method') }}</th>
          <th>{{ __('Payment Status') }}</th>
          <th>{{ __('Status') }}</th>
          <th>{{ __('Driver Appointed') }}</th>
          <th>{{ __('Driver Name') }}</th>
          <th>{{ __('Actions') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($orders as $order)
        <tr>
          <td></td>
          <td>{{ $order->id }}</td>
          <td>{{ $order->created_at->format('d M Y') }}</td>
          <td>{{ $order->user->name }}</td>
          <td>${{ number_format($order->subtotal, 2) }}</td>
          <td>${{ number_format($order->discount_amount, 2) }}</td>
          <td>{{ $order->coupon->code ?? 'N/A' }}</td>
          <td>${{ number_format($order->delivery_amount, 2) }}</td>
          <td>${{ number_format($order->total_amount, 2) }}</td>
          <td>{{ $order->payment_method }}</td>
          <td>
            @if ($order->payment_status === 'paid')
              <span class="badge bg-label-success">{{ __('Paid') }}</span>
            @else
              <span class="badge bg-label-danger">{{ __('Pending') }}</span>
            @endif
          </td>
          <td>
            <button type="button"
                    class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#changeStatusModal"
                    data-order-id="{{ $order->id }}"
                    data-order-status="{{ $order->status }}">
              <span class="badge bg-label-primary">{{ $order->status }}</span>
            </button>
          </td>
          <td>
            <span class="badge {{ $order->driver_appointed ? 'bg-label-success' : 'bg-label-danger' }}">
              {{ $order->driver_appointed ? __('Yes') : __('No') }}
            </span>
          </td>
          <td>{{ $order->driver->name ?? 'N/A' }}</td>
          <td>
            <div class="d-inline-block text-nowrap">
              <button class="btn btn-icon btn-text-secondary rounded-pill waves-effect waves-light"
                      data-bs-toggle="modal"
                      data-bs-target="#shareProject"
                      data-order-id="{{ $order->id }}">
                <i class="ti ti-edit"></i>
              </button>
              <a href="{{ route('orders.show', $order->id)}}" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill">
                <i class="ti ti-eye ti-md"></i>
              </a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- مودال تغيير الحالة -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="changeStatusForm" method="POST" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">{{ __('Change Order Status') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
          </div>
          <div class="modal-body">
            <select class="form-select" name="status" id="modal_order_status" required>
              @foreach ([
                'pending' => __('Pending'),
                'processing' => __('Processing'),
                'shipped' => __('Shipped'),
                'on_way' => __('On Way'),
                'delivered' => __('Delivered'),
                'cancelled' => __('Cancelled'),
                'refunded' => __('Refunded'),
                'failed' => __('Failed'),
              ] as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
          </div>
        </form>
      </div>
    </div>

    <!-- مودال تعيين السائق -->
  @include('admin.orders.appoint-driver', ['drivers' => $drivers])

  </div>
</div>
@endsection
