<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingLineItem;
use App\Models\BookingService;
use App\Models\BookingPayment;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::query()
            ->latest()
            ->paginate();
    }

    public function store(Request $request, Booking $booking)
    {
        // Define validation rules for specific fields
        $validationRules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
        ];

        // Validate the specific fields
        $request->validate($validationRules);
        // Update the model with all form fields
        $booking->update($request->except(['product','lineitems','services','payments']));

        // Use the sync method to update the selected attributes
        $booking->attributes()->sync($request->input('attributes', []));
        // Use the sync method to update the selected features
        $booking->features()->sync($request->input('features', []));


        // Handle lineitems if provided
        if (isset($request['lineitems'])) {
            // Create and associate line items
            $lineItems = [];
            foreach ($request['lineitems'] as $lineitem) {
                $lineItems[] = new ProductLineItem([
                    'name' => $lineitem['name'],
                    'value' => $lineitem['value'],
                    'value_type' => $lineitem['value_type'],
                    'apply_on' => $lineitem['apply_on'],
                    'is_required' => $lineitem['is_required'],
                    'image' => $lineitem['image'],
                    'display_order' => $lineitem['display_order'],
                ]);
            }

            $product->lineitems()->saveMany($lineItems);
        }

        return response()->json(['message' => 'success']);
    }

    public function edit(Booking $booking)
    {
        $booking->load('lineitems')->load('services')->load('payments')->load('product');

        return $booking;
    }

    public function update(Request $request, Booking $booking)
    {
        // Define validation rules for specific fields
        $validationRules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
        ];

        // Validate the specific fields
        $request->validate($validationRules);

        // Update the model with all form fields
        $booking->update($request->except(['product','lineitems','services','payments']));

        if (isset($request['lineitems'])) {
            // Delete existing line items for the product
            $booking->lineitems()->delete();
        
            // Create and associate new line items
            $lineItems = [];
            foreach ($request['lineitems'] as $lineitem) {
                $lineItems[] = new BookingLineItem([
                    'name' => $lineitem['name'],
                    'price' => $lineitem['price'],
                    // 'value_type' => $lineitem['value_type'],
                    // 'apply_on' => $lineitem['apply_on'],
                    // 'is_required' => $lineitem['is_required'],
                    // 'image' => $lineitem['image'],
                    'display_order' => $lineitem['display_order'],
                ]);
            }
        
            $booking->lineitems()->saveMany($lineItems);
        }


        if (isset($request['services'])) {
            // Delete existing services for the product
            $booking->services()->delete();
        
            // Create and associate new services
            $services = [];
            foreach ($request['services'] as $service) {
                $services[] = new BookingService([
                    'name' => $service['name'],
                    // 'icon' => $service['icon'],
                    // 'image' => $service['image'],
                    'price' => $service['price'],
                    'description' => $service['description'],
                    'display_order' => $service['display_order'],
                ]);
            }
        
            $booking->services()->saveMany($services);
        }
        

        if (isset($request['payments'])) {
            $booking->payments()->delete();
            
            $payments = [];
            
            foreach ($request['payments'] as $price) {
                $payments[] = new BookingPayment([
                    'title' => $price['title'],
                    'date_due' => $price['date_due'],
                    'date_paid' => $price['date_paid'],
                    'status' => $price['status'],
                    'amount' => $price['amount'],
                    'method' => $price['method'],
                    // 'display_order' => $price['display_order'],
                ]);
            }
        
            $booking->payments()->saveMany($payments);
        }

        return response()->json(['success' => true]);
    }


    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json(['success' => true], 200);
    }
}
