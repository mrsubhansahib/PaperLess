<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class RoutingController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    /**
     * Display a view based on first route param
     */
    public function root(Request $request, $first)
    {
        if (View::exists($first)) {
            return view($first);
        }
        return view('pages.404'); // fallback view
    }

    /**
     * second level route
     */
    public function secondLevel(Request $request, $first, $second)
    {
        $viewPath = $first . '.' . $second;

        if (View::exists($viewPath)) {
            return view($viewPath);
        }
        return view('pages.404'); // fallback
    }

    /**
     * livewire view
     */
    public function livewireView($first)
    {
        if (View::exists($first)) {
            return view($first);
        }
        return view('pages.404'); // fallback
    }
}
