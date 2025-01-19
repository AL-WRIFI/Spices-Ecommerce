<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EcommerceReferrals extends Controller
{
  public function index()
  {
    return view('content.apps.app-ecommerce-referrals');
  }
}
