<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    /**
     * Syarat dan Ketentuan Layanan (Terms of Service)
     * Sesuai UU ITE No. 1/2024 & PP No. 80/2019 (PMSE)
     */
    public function terms()
    {
        return view('legal.terms');
    }

    /**
     * Kebijakan Privasi & Perlindungan Data Pribadi
     * Sesuai UU No. 27/2022 (Perlindungan Data Pribadi - PDP)
     */
    public function privacy()
    {
        return view('legal.privacy');
    }

    /**
     * Kebijakan Hak Cipta, Lisensi & DMCA / Prosedur Takedown
     * Sesuai UU Hak Cipta No. 28/2014 & SE Menkominfo No. 5/2016
     */
    public function copyright()
    {
        return view('legal.copyright');
    }

    /**
     * Kebijakan Pengembalian Dana (Refund Policy) Produk Digital
     * Sesuai UU Perlindungan Konsumen No. 8/1999 & Permendag No. 31/2023
     */
    public function refund()
    {
        return view('legal.refund');
    }
}
