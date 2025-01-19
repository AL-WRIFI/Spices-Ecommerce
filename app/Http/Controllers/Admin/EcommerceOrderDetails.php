<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EcommerceOrderDetails extends Controller
{
  public function index()
  {
    return view('content.apps.app-ecommerce-order-details');
  }
}
