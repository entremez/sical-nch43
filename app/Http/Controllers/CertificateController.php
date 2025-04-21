<?php

namespace App\Http\Controllers;

use App\Imports\CertificatesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CertificateController extends Controller
{
    public function index()
    {

        Excel::import(new CertificatesImport, public_path("sis2_e.xlsx"));
    }
}
