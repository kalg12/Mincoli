<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // TODO: Implement cart logic with sessions/database
        return view('cart');
    }

    public function add(Request $request)
    {
        // TODO: Implement add to cart logic
        return back()->with('success', 'Producto agregado al carrito');
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update cart item logic
        return back()->with('success', 'Carrito actualizado');
    }

    public function remove($id)
    {
        // TODO: Implement remove from cart logic
        return back()->with('success', 'Producto eliminado del carrito');
    }

    public function clear()
    {
        // TODO: Implement clear cart logic
        return back()->with('success', 'Carrito vaciado');
    }
}
