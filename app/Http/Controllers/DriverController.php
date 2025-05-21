<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Location\Country;
use App\Models\Location\Region;
use App\Models\Location\City;
use App\Models\Location\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{

    public function index()
    {
        $drivers = Driver::with('region')->latest()->get();
        $totalDrivers = Driver::count();
        $activeDrivers = Driver::where('status', 'active')->count();
        $inactiveDrivers = Driver::where('status', 'inactive')->count();
        $driversWithOrders = Driver::where('has_order', 1)->count();
        $totalSalary = Driver::sum('salary');
        $averageSalary = $totalDrivers > 0 ? $totalSalary / $totalDrivers : 0;
        $newDrivers = Driver::where('created_at', '>=', now()->subDays(7))->count();

        $regions = Region::all();

        return view('admin.drivers.index', [
            'drivers' => $drivers,
            'totalDrivers' => $totalDrivers,
            'activeDrivers' => $activeDrivers,
            'inactiveDrivers' => $inactiveDrivers,
            'driversWithOrders' => $driversWithOrders,
            'totalSalary' => $totalSalary,
            'averageSalary' => $averageSalary,
            'newDrivers' => $newDrivers,
            'regions' => $regions,
        ]);
    }


    public function create()
    {
        $countries = Country::all();
        $regions = Region::all();
        $cities = City::all();
        $districts = District::all();
        return view('admin.drivers.create', compact('countries', 'regions', 'cities', 'districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'salary' => 'required',
            'image' => 'nullable|image',
            'identity_image' => 'required|image',
            'identity_number' => 'required|string|max:255',
            'iban' => 'required|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'region_id' => 'nullable|exists:regions,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $url = Storage::url($path);
            $imagePath = $url;
        }

        if ($request->hasFile('identity_image')) {
            $path = $request->file('identity_image')->store('images', 'public');
            $url = Storage::url($path);
            $identityImagePath = $url;
        }

        Driver::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'salary' => $request->salary,
            'image' => $imagePath,
            'identity_image' => $identityImagePath,
            'identity_number' => $request->identity_number,
            'iban' => $request->iban,
            'country_id' => $request->country_id,
            'region_id' => $request->region_id,
            'city_id' => $request->city_id,
            'district_id' => $request->district_id,
            'address' => $request->address,
            'status' => $request->status,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('drivers.index')->with('success', 'تم إضافة السائق بنجاح.');
    }

   
    public function show(Driver $driver)
    {
        return view('admin.drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        $countries = Country::all();
        $regions = Region::all();
        $cities = City::all();
        $districts = District::all();
        return view('admin.drivers.create', compact('driver', 'countries', 'regions', 'cities', 'districts'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'salary' => 'required|numeric',
            'image' => 'nullable|image',
            'identity_image' => 'nullable|image',
            'identity_number' => 'required|string|max:255',
            'iban' => 'required|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'region_id' => 'nullable|exists:regions,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $oldImagePath = $driver->image;
            $driver->image = $imagePath;
            Storage::delete($oldImagePath);
        }

        if ($request->hasFile('identity_image')) {
            $identityImagePath = $request->file('identity_image')->store('images', 'public');
            $oldImagePath = $driver->identity_image;
            $driver->identity_image = $identityImagePath;
            Storage::delete($oldImagePath);
        }

        $driver->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'salary' => $request->salary,
            'identity_number' => $request->identity_number,
            'iban' => $request->iban,
            'country_id' => $request->country_id,
            'region_id' => $request->region_id,
            'city_id' => $request->city_id,
            'district_id' => $request->district_id,
            'address' => $request->address,
            'status' => $request->status,
            'password' => $request->password ? Hash::make($request->password) : $driver->password,
        ]);

        return redirect()->route('drivers.index')->with('success', 'تم تحديث بيانات السائق بنجاح.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'تم حذف السائق بنجاح.');
    }
}