<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::findOrFail(1);
        return view('settings.edit', compact('setting'));
    }

    public function store(Request $request)
    {
        $setting = Setting::findOrFail(1);

        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('settings', 'public');
        }

        $setting->logo = $image_path;
        $setting->app_name = $request->app_name;
        $setting->currency_symbol = $request->currency_symbol;
        $setting->app_description = $request->app_description;
        $setting->warning_quantity = $request->warning_quantity;
        $setting->phone = $request->phone;
        $setting->email = $request->email;
        $setting->vat_registered = $request->vat_registered;

        if (! $setting->save()) {
            return redirect()->back()->with('error', __('Error updating settings'));
        }

        return redirect()->route('settings.index');
    }
}
