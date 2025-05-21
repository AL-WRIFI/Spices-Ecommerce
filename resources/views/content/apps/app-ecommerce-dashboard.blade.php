@extends('layouts/layoutMaster')

@section('title', __('eCommerce Dashboard - Apps'))

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/apex-charts/apexcharts.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
  ])
@endsection

@section('page-script')
@vite(['resources/assets/js/app-ecommerce-dashboard.js'])
@endsection


@section('content')
<div class="row g-6">
  <!-- View sales -->
  <div class="col-xl-4">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-7">
          <div class="card-body text-nowrap">
            <h5 class="card-title mb-0">{{ __('Congratulations') }} {{ $greetingName }}! 🎉</h5>
            <p class="mb-2">{{ __('Best seller of the month') }}</p>
            <h4 class="text-primary mb-1">${{ number_format($currentMonthSales, 2) }}</h4>
            {{-- Removed "View Sales" button for now, as it was a placeholder. Add back if needed with translation. --}}
            {{-- <a href="javascript:;" class="btn btn-primary">{{ __('View Sales') }}</a> --}}
          </div>
        </div>
        <div class="col-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img src="{{ asset('assets/img/illustrations/card-advance-sale.png')}}" height="140" alt="view sales">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- View sales -->

  <!-- Statistics -->
  <div class="col-xl-8 col-md-12">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title mb-0">{{ __('Statistics') }}</h5>
        <small class="text-muted">{{ __('Updated') }} {{ \Carbon\Carbon::now()->shortRelativeDiffForHumans() }}</small>
      </div>
      <div class="card-body d-flex align-items-end">
        <div class="w-100">
          <div class="row gy-3">
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-primary me-4 p-2"><i class="ti ti-chart-pie-2 ti-lg"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ number_format($totalOrdersCount) }}</h5>
                  <small>{{ __('Sales') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-info me-4 p-2"><i class="ti ti-users ti-lg"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ number_format($totalCustomers) }}</h5>
                  <small>{{ __('Customers') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-danger me-4 p-2"><i class="ti ti-shopping-cart ti-lg"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ number_format($totalProducts) }}</h5>
                  <small>{{ __('Products') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-success me-4 p-2"><i class="ti ti-currency-dollar ti-lg"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">${{ number_format($totalSalesAmount, 2) }}</h5>
                  <small>{{ __('Revenue') }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Statistics -->

  <div class="col-xxl-4 col-12">
    <div class="row g-6">
      <!-- Profit last month -->
      <div class="col-xl-6 col-sm-6">
        <div class="card h-100">
          <div class="card-header pb-0">
            <h5 class="card-title mb-1">{{ __('Profit') }}</h5>
            <p class="card-subtitle">{{ __('Last Month') }}</p>
          </div>
          <div class="card-body">
            <div id="profitLastMonth"></div>
            <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
              <h4 class="mb-0">${{ number_format($profitLastMonth, 2) }}</h4>
              <small class="{{ $profitPercentageChange >= 0 ? 'text-success' : 'text-danger' }}">
                {{ $profitPercentageChange >= 0 ? '+' : '' }}{{ number_format($profitPercentageChange, 2) }}%
              </small>
            </div>
          </div>
        </div>
      </div>
      <!--/ Profit last month -->

      <!-- Expenses -->
      <div class="col-xl-6 col-sm-6">
        <div class="card h-100">
          <div class="card-header pb-2">
            <h5 class="card-title mb-1">${{ number_format($expensesLastMonth, 2) }}</h5>
            <p class="card-subtitle">{{ __('Expenses') }}</p>
          </div>
          <div class="card-body">
            <div id="expensesChart"></div>
            <div class="mt-3 text-center">
              <small class="text-muted mt-3">${{ number_format($expensesMoreThanLastMonth, 2) }} {{ __('Expenses more than last month') }}</small>
            </div>
          </div>
        </div>
      </div>
      <!--/ Expenses -->

      <!-- Generated Leads -->
      <div class="col-xl-12">
        <div class="card h-100">
          <div class="card-body d-flex justify-content-between">
            <div class="d-flex flex-column">
              <div class="card-title mb-auto">
                <h5 class="mb-0 text-nowrap">{{ __('Generated Leads') }}</h5>
                <p class="mb-0">{{ __('Monthly Report') }}</p>
              </div>
              <div class="chart-statistics">
                <h3 class="card-title mb-0">{{ number_format($leadsThisMonth) }}</h3>
                <p class="{{ $leadsPercentageChange >= 0 ? 'text-success' : 'text-danger' }} text-nowrap mb-0">
                    <i class='ti {{ $leadsPercentageChange >= 0 ? 'ti-chevron-up' : 'ti-chevron-down' }} me-1'></i>
                    {{ number_format(abs($leadsPercentageChange), 1) }}%
                </p>
              </div>
            </div>
            <div id="generatedLeadsChart"></div>
          </div>
        </div>
      </div>
      <!--/ Generated Leads -->
    </div>
  </div>

  <!-- Revenue Report -->
  <div class="col-xxl-8">
    <div class="card h-100">
      <div class="card-body p-0">
        <div class="row row-bordered g-0">
          <div class="col-md-8 position-relative p-6">
            <div class="card-header d-inline-block p-0 text-wrap position-absolute">
              <h5 class="m-0 card-title">{{ __('Revenue Report') }}</h5>
            </div>
            <div id="totalRevenueChart" class="mt-n1"></div>
          </div>
          <div class="col-md-4 p-4">
            <div class="text-center mt-5">
              <div class="dropdown">
                <button class="btn btn-sm btn-label-primary dropdown-toggle" type="button" id="budgetId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <script>
                  document.write(new Date().getFullYear())
                  </script>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="budgetId">
                  <a class="dropdown-item prev-year1" href="javascript:void(0);">
                    <script>
                    document.write(new Date().getFullYear() - 1)
                    </script>
                  </a>
                  <a class="dropdown-item prev-year2" href="javascript:void(0);">
                    <script>
                    document.write(new Date().getFullYear() - 2)
                    </script>
                  </a>
                  <a class="dropdown-item prev-year3" href="javascript:void(0);">
                    <script>
                    document.write(new Date().getFullYear() - 3)
                    </script>
                  </a>
                </div>
              </div>
            </div>
            <h3 class="text-center pt-8 mb-0">${{ number_format($currentYearRevenue, 2) }}</h3>
            <p class="mb-8 text-center"><span class="fw-medium text-heading">{{ __('Budget') }}: </span>${{ number_format($budgetForYear, 2) }}</p>
            <div class="px-3">
              <div id="budgetChart"></div>
            </div>
            <div class="text-center mt-8">
              <button type="button" class="btn btn-primary">{{ __('Increase Budget') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Revenue Report -->

  <!-- Earning Reports -->
  <div class="col-xxl-4 col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1">{{ __('Earning Reports') }}</h5>
          <p class="card-subtitle">{{ __('Weekly Earnings Overview') }}</p>
        </div>
        <div class="dropdown">
          <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" id="earningReports" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti ti-dots-vertical ti-md text-muted"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReports">
            <a class="dropdown-item" href="javascript:void(0);">{{ __('Download') }}</a>
            <a class="dropdown-item" href="javascript:void(0);">{{ __('Refresh') }}</a>
            <a class="dropdown-item" href="javascript:void(0);">{{ __('Share') }}</a>
          </div>
        </div>
      </div>
      <div class="card-body pb-0">
        <ul class="p-0 m-0">
          <li class="d-flex align-items-center mb-5">
            <div class="me-4">
              <span class="badge bg-label-primary rounded p-1_5"><i class='ti ti-chart-pie-2 ti-md'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">{{ __('Net Profit') }}</h6>
                <small class="text-body">{{ number_format($weeklySalesCount) }} {{ __('Sales') }}</small>
              </div>
              <div class="user-progress d-flex align-items-center gap-4">
                <small>${{ number_format($weeklyNetProfit, 2) }}</small>
                <div class="d-flex align-items-center gap-1">
                  <i class='ti ti-chevron-up text-success'></i>
                  <small class="text-muted">{{ number_format($weeklyNetProfitIncrease,1) }}%</small>
                </div>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-5">
            <div class="me-4">
              <span class="badge bg-label-success rounded p-1_5"><i class='ti ti-currency-dollar ti-md'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">{{ __('Total Income') }}</h6>
                <small class="text-body">{{ __('Sales, Affiliation') }}</small>
              </div>
              <div class="user-progress d-flex align-items-center gap-4">
                <small>${{ number_format($weeklyTotalIncome, 2) }}</small>
                <div class="d-flex align-items-center gap-1">
                  <i class='ti ti-chevron-up text-success'></i>
                  <small class="text-muted">{{ number_format($weeklyTotalIncomeIncrease,1) }}%</small>
                </div>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-5">
            <div class="me-4">
              <span class="badge bg-label-secondary text-body rounded p-1_5"><i class='ti ti-credit-card ti-md'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">{{ __('Total Expenses') }}</h6>
                <small class="text-body">{{ __('ADVT, Marketing') }}</small>
              </div>
              <div class="user-progress d-flex align-items-center gap-4">
                <small>${{ number_format($weeklyTotalExpenses, 2) }}</small>
                <div class="d-flex align-items-center gap-1">
                  <i class='ti ti-chevron-up text-success'></i>
                  <small class="text-muted">{{ number_format($weeklyExpensesIncrease,1) }}%</small>
                </div>
              </div>
            </div>
          </li>
        </ul>
        <div id="reportBarChart"></div>
      </div>
    </div>
  </div>
  <!--/ Earning Reports -->

  <!-- Popular Product -->
  <div class="col-xxl-4 col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title m-0 me-2">
          <h5 class="mb-1">{{ __('Popular Products') }}</h5>
          <p class="card-subtitle">{{ __('Total') }} {{ number_format($popularProducts->sum('total_sold')) }} {{ __('Sold') }}</p>
        </div>
        <div class="dropdown">
          <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" id="popularProduct" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti ti-dots-vertical ti-md text-muted"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="popularProduct">
            <a class="dropdown-item" href="javascript:void(0);">{{ __('Last 28 Days') }}</a>
            <a class="dropdown-item" href="javascript:void(0);">{{ __('Last Month') }}</a>
            <a class="dropdown-item" href="javascript:void(0);">{{ __('Last Year') }}</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          @forelse ($popularProducts as $product)
          <li class="d-flex {{ !$loop->last ? 'mb-6' : '' }}">
            <div class="me-4">
              <img src="{{ $product->image ? $product->image : asset('assets/img/products/default.png') }}" alt="{{ $product->name }}" class="rounded" width="46">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">{{ $product->name }}</h6> {{-- Product name is dynamic, not translated here --}}
                <small class="text-body d-block">{{ __('Item') }}: #P-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</small>
              </div>
              <div class="user-progress d-flex align-items-center gap-1">
                <p class="mb-0">${{ number_format($product->price, 2) }}</p>
              </div>
            </div>
          </li>
          @empty
          <li class="text-center">{{ __('No popular products found.') }}</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
  <!--/ Popular Product -->

  <!-- Orders by Status tabs-->
  <div class="col-xxl-4 col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1">{{ __('Orders by Status') }}</h5>
          <p class="card-subtitle">{{ $deliveriesInProgressCount }} {{ __('deliveries in progress') }}</p>
        </div>
        <div class="dropdown">
          <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" id="salesByCountryTabs" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti ti-dots-vertical ti-md text-muted"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesByCountryTabs">
            <a class="dropdown-item" href="javascript:void(0);">{{ __('Select All') }}</a>
            <a class="dropdown-item" href="javascript:void(0);">{{ __('Refresh') }}</a>
            <a class="dropdown-item" href="javascript:void(0);">{{ __('Share') }}</a>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="nav-align-top">
          <ul class="nav nav-tabs nav-fill rounded-0 timeline-indicator-advanced" role="tablist">
            <li class="nav-item">
              <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-new" aria-controls="navs-justified-new" aria-selected="true">{{ __('New') }} ({{ $newOrders->count() }})</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-link-preparing" aria-controls="navs-justified-link-preparing" aria-selected="false">{{ __('Preparing') }} ({{ $preparingOrders->count() }})</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-link-shipping" aria-controls="navs-justified-link-shipping" aria-selected="false">{{ __('Shipping') }} ({{ $shippingOrders->count() }})</button>
            </li>
          </ul>
          <div class="tab-content border-0 mx-1">
            {{-- New Orders Tab --}}
            <div class="tab-pane fade show active" id="navs-justified-new" role="tabpanel">
              @forelse($newOrders as $order)
              <ul class="timeline mb-0 {{ !$loop->last ? 'border-bottom border-dashed pb-4 mb-4' : '' }}">
                <li class="timeline-item ps-6 border-left-dashed">
                  <span class="timeline-indicator-advanced timeline-indicator-secondary border-0 shadow-none">
                    <i class='ti ti-file-text'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-secondary text-uppercase">{{ __('Order') }} #{{ $order->id }}</small>
                    </div>
                    <h6 class="my-50">{{ $order->user ? $order->user->name : 'N/A' }}</h6>
                    <p class="text-body mb-0">
                        @if(is_string($order->shipping_address))
                            {{ $order->shipping_address }}
                        @elseif(is_array($order->shipping_address))
                            {{ $order->shipping_address['address_line_1'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}
                        @else
                            {{ $order->user ? ($order->user->address ?? __('Address not available')) : __('Address not available') }}
                        @endif
                    </p>
                    <small>{{ __('Total') }}: ${{ number_format($order->total_amount, 2) }}</small>
                  </div>
                </li>
              </ul>
              @empty
              <p class="text-center py-3">{{ __('No new orders.') }}</p>
              @endforelse
            </div>

            {{-- Preparing Orders Tab --}}
            <div class="tab-pane fade" id="navs-justified-link-preparing" role="tabpanel">
              @forelse($preparingOrders as $order)
              <ul class="timeline mb-0 {{ !$loop->last ? 'border-bottom border-dashed pb-4 mb-4' : '' }}">
                 <li class="timeline-item ps-6 border-left-dashed">
                  <span class="timeline-indicator-advanced timeline-indicator-warning border-0 shadow-none">
                    <i class='ti ti-settings-cog'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-warning text-uppercase">{{ __('Order') }} #{{ $order->id }}</small>
                    </div>
                    <h6 class="my-50">{{ $order->user ? $order->user->name : 'N/A' }}</h6>
                    <p class="text-body mb-0">
                        @if(is_string($order->shipping_address))
                            {{ $order->shipping_address }}
                        @elseif(is_array($order->shipping_address))
                            {{ $order->shipping_address['address_line_1'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}
                        @else
                             {{ $order->user ? ($order->user->address ?? __('Address not available')) : __('Address not available') }}
                        @endif
                    </p>
                    <small>{{ __('Total') }}: ${{ number_format($order->total_amount, 2) }}</small>
                  </div>
                </li>
              </ul>
              @empty
              <p class="text-center py-3">{{ __('No orders being prepared.') }}</p>
              @endforelse
            </div>

            {{-- Shipping Orders Tab --}}
            <div class="tab-pane fade" id="navs-justified-link-shipping" role="tabpanel">
              @forelse($shippingOrders as $order)
              <ul class="timeline mb-0 {{ !$loop->last ? 'border-bottom border-dashed pb-4 mb-4' : '' }}">
                 <li class="timeline-item ps-6 border-left-dashed">
                  <span class="timeline-indicator-advanced timeline-indicator-info border-0 shadow-none">
                    <i class='ti ti-truck-delivery'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-info text-uppercase">{{ __('Order') }} #{{ $order->id }}</small>
                    </div>
                    <h6 class="my-50">{{ $order->user ? $order->user->name : 'N/A' }}</h6>
                     <p class="text-body mb-0">
                        @if(is_string($order->shipping_address))
                            {{ $order->shipping_address }}
                        @elseif(is_array($order->shipping_address))
                            {{ $order->shipping_address['address_line_1'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}
                        @else
                            {{ $order->user ? ($order->user->address ?? __('Address not available')) : __('Address not available') }}
                        @endif
                    </p>
                     <small>{{ __('Total') }}: ${{ number_format($order->total_amount, 2) }}</small>
                  </div>
                </li>
              </ul>
              @empty
              <p class="text-center py-3">{{ __('No orders currently shipping.') }}</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection