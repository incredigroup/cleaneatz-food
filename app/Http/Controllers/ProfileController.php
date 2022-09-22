<?php

namespace App\Http\Controllers;

use App\Models\MealPlanOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends SiteController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    public function index()
    {
        return view('site.profile.general.index', ['user' => Auth::user()]);
    }

    public function postIndex(Request $request)
    {
        $input = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'username')->ignore(Auth::user()->id),
            ],
        ]);
        $input['username'] = $input['email'];

        Auth::user()->update($input);

        return redirect(route('profile.index'))->with('status', 'Your profile has been updated.');
    }

    public function newsletter()
    {
        return view('site.profile.newsletter');
    }

    public function billing()
    {
        return view('site.profile.billing.index', ['user' => Auth::user()]);
    }

    public function postBilling(Request $request)
    {
        $input = $request->validate([
            'billing_first_name' => 'nullable|string|max:255',
            'billing_last_name' => 'nullable|string|max:255',
            'billing_email' => 'nullable|email|max:255',
            'billing_phone' => 'nullable|string|max:255',
            'billing_address' => 'nullable|string|max:255',
            'billing_city' => 'nullable|string|max:255',
            'billing_state' => 'nullable|string|max:255',
            'billing_zip' => 'nullable|string|max:255',
        ]);

        Auth::user()->update($input);

        return redirect(route('profile.billing'))->with('status', 'Your profile has been updated.');
    }

    public function password()
    {
        return view('site.profile.password.index');
    }

    public function postPassword(Request $request)
    {
        $input = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($input['password']);
        $user->save();

        return redirect(route('profile.password'))->with(
            'status',
            'Your password has been updated.',
        );
    }

    public function orders()
    {
        $orders = MealPlanOrder::where('user_id', Auth::user()->id)
            ->with('store', 'satellite')
            ->orderBy('created_at', 'desc')
            ->where('transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->paginate(25);

        return view('site.profile.orders.index', ['orders' => $orders]);
    }
}
