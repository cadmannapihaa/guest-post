<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionPackageController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::latest()->paginate(10);
        return view('subscriptions.index', compact('packages'));
    }

    public function create()
    {
        return view('subscriptions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:150',
            'type'=>'required|string',
            'validity'=>'nullable|string',
            'exp_date'=>'nullable|date',
            'price'=>'required|numeric',
            'max_daily_limit'=>'required|integer',
            'max_limit'=>'required|integer',
            'is_active'=>'boolean',
            'is_visible'=>'boolean',
        ]);

        SubscriptionPackage::create($data);
        return redirect()->route('subscription-packages.index');
    }

    public function edit(SubscriptionPackage $subscriptionPackage)
    {
        return view('subscriptions.edit', compact('subscriptionPackage'));
    }

    public function update(Request $request, SubscriptionPackage $subscriptionPackage)
    {
        $data = $request->validate([
            'name'=>'required|string|max:150',
            'type'=>'required|string',
            'validity'=>'nullable|string',
            'exp_date'=>'nullable|date',
            'price'=>'required|numeric',
            'max_daily_limit'=>'required|integer',
            'max_limit'=>'required|integer',
            'is_active'=>'boolean',
            'is_visible'=>'boolean',
        ]);

        $subscriptionPackage->update($data);
        return redirect()->route('subscription-packages.index');
    }

    public function destroy(SubscriptionPackage $subscriptionPackage)
    {
        $subscriptionPackage->update(['is_deleted'=>true]);
        return back();
    }

    public function assignToUser(Request $request)
    {
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'package_id'=>'required|exists:subscription_packages,id',
            'expires_at'=>'required|date',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->subscription_package_id = $request->package_id;
        $user->subscription_started_at = now();
        $user->subscription_expires_at = $request->expires_at;
        $user->save();

        return back();
    }
}
