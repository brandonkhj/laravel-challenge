<?php

namespace App\Http\Controllers;

use App\Services\InternetServiceProvider\Mpt;
use App\Services\InternetServiceProvider\Ooredoo;
use Illuminate\Http\Request;

class InternetServiceProviderController extends Controller
{
    private $mpt;
    private $ooredoo;

    public function __construct(Mpt $mpt, Ooredoo $ooredoo)
    {
        $this->mpt = $mpt;
        $this->ooredoo = $ooredoo;
    }

    public function getMptInvoiceAmount(Request $request)
    {
        $this->mpt->setMonth($request->get('month') ?: 1);

        return response()->json([
            'data' => $this->mpt->calculateTotalAmount(),
        ]);
    }

    public function getOoredooInvoiceAmount(Request $request)
    {
        $this->ooredoo->setMonth($request->get('month') ?: 1);

        return response()->json([
            'data' => $this->ooredoo->calculateTotalAmount(),
        ]);
    }
}
