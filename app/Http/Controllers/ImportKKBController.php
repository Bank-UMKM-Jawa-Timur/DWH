<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportKKBController extends Controller
{
    public function index() {
        return view('pages.import_kkb.index');
    }
}
