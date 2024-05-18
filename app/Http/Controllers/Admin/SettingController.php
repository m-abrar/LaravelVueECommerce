<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        if (!$settings) {
            return config('settings.default');
        }

        return $settings;
    }

    public function update()
    {
        $validator = Validator::make(request()->all(), [
            // Define your validation rules here for the fields you want to validate
            'app_name' => ['required', 'string'],
            'date_format' => ['required', 'string'],
            'pagination_limit' => ['required', 'integer', 'min:1', 'max:100'],
            // Add validation rules for logo and favicon
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle file uploads
        $settingsData = request()->all();
        if (request()->hasFile('logo')) {
            $logo = request()->file('logo');
            $logoPath = $logo->store('logos', 'public');
            $settingsData['logo'] = $logoPath;
        }

        if (request()->hasFile('favicon')) {
            $favicon = request()->file('favicon');
            $faviconPath = $favicon->store('favicons', 'public');
            $settingsData['favicon'] = $faviconPath;
        }

        // Process and store settings data
        foreach ($settingsData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }

        // Flush cache
        Cache::flush('settings');

        return response()->json(['success' => true]);
    }
}
