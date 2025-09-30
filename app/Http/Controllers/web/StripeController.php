<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Task; // Assuming you have a Task model
use App\Models\User;
use Illuminate\Support\Facades\Log;





class StripeController extends Controller
{
    public $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.api_key.secret'));
    }

    public function pay(Request $request)
    {
        Log::info($request);
        // Retrieve the price from the query string, default to the original price (2000 cents) if not provided
        $price = $request->query('price', 2000);

        // Retrieve the logged-in user
        $user = auth()->user();

        // Check if the user has a discount (from a 'discount' column)
        $discountPercent = $user->discount ?? 0; // Default to 0% if no discount

        // Initialize Stripe client
        $stripe = new StripeClient('sk_test_51Q8NOFG3P0lzddKXekGLMbGqB3g4Y8OGq59rqQwb898OfyLVIt6uQQxPd8LWYrx75K4PPolSxVFLjXWZJHSptMxF00At5HP75m');

        $promotionCodeId = null; // Variable to store promotion code ID
        Log::info($discountPercent);

        if ($discountPercent > 0) {
            // Create a coupon based on the user's discount
            $coupon = $stripe->coupons->create([
                'duration' => 'forever', // Applies only once per payment
                'percent_off' => $discountPercent,
            ]);

            // Create a promotion code using the coupon ID
            $promotionCode = $stripe->promotionCodes->create([
                'coupon' => $coupon->id,
                "active" => true,
            ]);

            $promotionCodeId = $promotionCode->id; // Store the promotion code ID
        }

        // Create the Stripe Checkout session
        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Task Payment',
                    ],
                    'unit_amount' => $price, // Use the updated price from the query string
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'discounts' => $promotionCodeId ? [[
                'promotion_code' => $promotionCodeId,  // Apply the promotion code
            ]] : [],  // If no discount, leave empty
            'success_url' => route('tasks.payment.success'), // Success URL
            'cancel_url' => route('tasks.payment.cancel'),  // Cancel URL
        ]);

        // Redirect to the Stripe Checkout session URL
        return redirect($session->url);
    }




    public function store(Request $request)
{
    if ($request->get('success') == 'true') {
        // Payment was successful, create the task

        // Validate task data
        $validated = $request->validate([
            'task_description_en' => 'required|string|max:255',
            'task_description_ar' => 'required|string|max:255',
            'dead_line' => 'required|date',
            'assign_users' => 'required|array',
            'assign_categories' => 'required|array',
        ]);

        // Store the task in the database
        $task = new Task();
        $task->description_en = $request->task_description_en;
        $task->description_ar = $request->task_description_ar;
        $task->deadline = $request->dead_line;
        $task->save();

        // Attach users and categories
        $task->users()->attach($request->assign_users);
        $task->categories()->attach($request->assign_categories);

        return view('home');  // Ensure you have a 'home.blade.php' view

        //return redirect()->route('tasks.index')->with('success', __('Task created successfully!'));
    } else {
        return view('home');  // Ensure you have a 'home.blade.php' view

        //return redirect()->route('tasks.index')->with('error', __('Payment failed. Task was not created.'));
    }
}

public function updateDiscountAjax(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'discount' => 'required|numeric|min:0|max:100',
    ]);

    $user = User::findOrFail($request->user_id);
    $user->discount = $request->discount;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Discount updated successfully',
    ]);
}


   // Create new discount for a user via AJAX
   public function createDiscountAjax(Request $request)
   {
       $request->validate([
           'email' => 'required|email|exists:users,email',
           'discount' => 'required|numeric|min:0|max:100',
       ]);

       $user = User::where('email', $request->email)->first();
       if ($user) {
           $user->discount = $request->discount;
           $user->save();

           return response()->json([
               'success' => true,
               'message' => 'Discount created successfully for user ' . $user->email,
           ]);
       }

       return response()->json([
           'success' => false,
           'message' => 'User not found',
       ]);
   }




public function Discount()
{


    $users=User::all();
    return view('discount', compact('users'));
}





}
