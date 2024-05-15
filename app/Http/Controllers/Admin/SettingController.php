<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

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
            // You can add more validation rules for other fields if needed
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Combine both validated and non-validated fields for processing
        $settingsData = array_merge(request()->all(), $validator->getData());

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
