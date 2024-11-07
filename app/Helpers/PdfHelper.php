<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class PdfHelper
{
    /**
     * Generate PDF content as string from Blade view.
     *
     * @param string $view The Blade view to render.
     * @param array $data Data to pass to the view.
     * @param string $paperSize The paper size for the PDF (optional).
     * @return string The PDF content as string.
     */
    public static function generatePdfContent($view, $data = [], $paperSize = 'Folio')
    {
        $pdf = Pdf::loadHtml(Blade::render($view, $data))->setPaper($paperSize);
        return $pdf->output();
    }

    /**
     * Stream the PDF directly to the browser.
     *
     * @param string $view The Blade view to render.
     * @param array $data Data to pass to the view.
     * @param string $filename The name for the PDF file.
     * @param string $paperSize The paper size for the PDF (optional).
     * @return \Illuminate\Http\Response
     */
    public static function streamPdf($view, $data, $filename, $paperSize = 'Folio')
    {
        return response()->stream(function () use ($view, $data, $paperSize) {
            $pdf = Pdf::loadHtml(Blade::render($view, $data))->setPaper($paperSize);
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"',
        ]);
    }
}
