<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index() {
        return config('auth.password_timeout'). now() ;
    }

    public function about ($id = null) {
        return config('app.url'). $id ;
    }
}
