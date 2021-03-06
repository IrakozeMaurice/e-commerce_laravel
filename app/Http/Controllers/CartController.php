<?php

namespace App\Http\Controllers;

use App\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $mightAlsoLike = Product::mightAlsoLike()->get();
        return view('cart', compact('mightAlsoLike'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $duplicates = Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->id === $request->id;
        });

        if ($duplicates->isNotEmpty()) {
            return redirect()->route('cart.index')->with('success-message', 'Item is already in your cart!');
        }

        Cart::add($request->id, $request->name, 1, $request->price)
            ->associate('App\Product');

        return redirect()->route('cart.index')->with('success-message', 'item was added to your cart!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric|between:1,5'
        ]);

        if ($validator->fails()) {
            session()->flash('errors', collect(['Quantity must be between 1 and 5']));
            return response()->json(['success' => false], 500);
        }

        Cart::update($id, $request->quantity);
        session()->flash('success-message', 'Quantity was updated successfully');
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Cart::remove($id);

        return redirect()->back()->with('success-message', 'Item(s) has been removed!');
    }

    //switch item from cart to save for later

    public function switchToSaveForLater($id)
    {
        $item = Cart::get($id);
        Cart::remove($id);  //remove item from list of products in cart

        $duplicates = Cart::instance('saveForLater')->search(function ($cartItem, $rowId) use ($id) {
            return $rowId === $id;
        });

        if ($duplicates->isNotEmpty()) {
            return redirect()->route('cart.index')->with('success-message', 'Item is already saved for Later!');
        }

        Cart::instance('saveForLater')->add($item->id, $item->name, 1, $item->price)
            ->associate('App\Product');

        return redirect()->route('cart.index')->with('success-message', 'item has been saved for later!');
    }
}
