<?php

namespace App\Http\Controllers;

use App\Models\HotelCase;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public static function download($hotelCaseId)
    {
        $hotelCase = HotelCase::with('guideReport')->findOrFail($hotelCaseId);

        $pdf = Pdf::loadView('invoices.hotel_case', [
            'hotelCase' => $hotelCase,
            'guideReport' => $hotelCase->guideReport,
        ]);

        $fileName = 'invoice_' . $hotelCase->id . '.pdf';
        return $pdf->download($fileName);
    }
} 