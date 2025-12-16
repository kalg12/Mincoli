<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PagesController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function shipping(): View
    {
        return view('pages.shipping-policies');
    }

    public function returns(): View
    {
        return view('pages.returns');
    }

    public function faq(): View
    {
        return view('pages.faq');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function privacy(): View
    {
        return view('pages.privacy-policy');
    }

    public function legal(): View
    {
        return view('pages.legal-notice');
    }
}
