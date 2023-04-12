<?php


use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterSellerController extends Controller
{
        /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('seller.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roleId' => ['required','integer']
        ]);

        $seller =  Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roleId'=> $request->roleId
        ]);

        event(new Registered($seller));

        Auth::login($seller);

        return redirect(RouteServiceProvider::HOME);
    }
}