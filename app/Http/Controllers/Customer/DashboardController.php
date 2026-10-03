<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Address;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();
        $addressCount = Address::where('user_id', $user->id)->count();

        return view('customer.dashboard.index', compact('user', 'recentOrders', 'wishlistCount', 'addressCount'));
    }

    public function profile()
    {
        return view('customer.dashboard.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.dashboard.orders', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');
        return view('customer.dashboard.order-show', compact('order'));
    }

    public function addresses()
    {
        $addresses = Address::where('user_id', auth()->id())->latest()->get();
        return view('customer.dashboard.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^01[0-9]{9}$/',
            'governorate' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'required|string|max:1000',
            'landmark' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:50',
            'apartment' => 'nullable|string|max:50',
            'is_default' => 'boolean',
            'type' => 'nullable|string|max:50',
        ]);

        $data['user_id'] = auth()->id();

        if ($data['is_default'] ?? false) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        Address::create($data);

        return back()->with('success', 'Address added successfully.');
    }

    public function updateAddress(Request $request, Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^01[0-9]{9}$/',
            'governorate' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'required|string|max:1000',
            'landmark' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:50',
            'apartment' => 'nullable|string|max:50',
            'is_default' => 'boolean',
            'type' => 'nullable|string|max:50',
        ]);

        if ($data['is_default'] ?? false) {
            Address::where('user_id', auth()->id())->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($data);

        return back()->with('success', 'Address updated successfully.');
    }

    public function deleteAddress(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();
        return back()->with('success', 'Address deleted successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }
}
