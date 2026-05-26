<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

}