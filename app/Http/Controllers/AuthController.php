<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\ShoppingCart;

/**
 * Write static login information to the session.
 * Use for test purposes.
 */
class AuthController extends Controller
{
    /**
     * Write static login information to the session.
     *
     * @param Request $request The request object
     * @return \Illuminate\Http\RedirectResponse The redirect response
     */
    public function login(Request $request) {
        $request->session()->put('abalo_user', 'visitor');
        $request->session()->put('abalo_mail', 'visitor@abalo.example.com');
        $request->session()->put('abalo_time', time());

        $shoppingcart = ShoppingCart::where('creator_id', 1)->first();
        if(!$shoppingcart){
            ShoppingCart::create([
                'creator_id' => 1,
                'createdate' => now()
            ]);
        }
        $shoppingcart = ShoppingCart::where('creator_id', 1)->first();

        // Speichern Sie die ID des Warenkorbs in der Sitzung
        $request->session()->put('shoppingcart_id', $shoppingcart->id);
        $request->session()->save();
        return redirect()->route('haslogin');
    }
    public function logout(Request $request) {
        $request->session()->flush();
        return redirect()->route('haslogin');
    }


    public function isLoggedIn(Request $request) {
        if($request->session()->has('abalo_user')) {
            $r["user"] = $request->session()->get('abalo_user');
            $r["time"] = $request->session()->get('abalo_time');
            $r["mail"] = $request->session()->get('abalo_mail');
            $r["auth"] = "true";
        }
        else $r["auth"]="false";
        return response()->json($r);
    }
}
