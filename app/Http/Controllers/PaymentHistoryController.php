<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DOMDocument;
use XSLTProcessor;

class PaymentHistoryController extends Controller
{
    public function show()
    {
        // Load the XML file
        $xml = new DOMDocument();
        $xml->load( public_path('receipt.xml'));

        // Load the XSL file
        $xsl = new DOMDocument();
        $xsl->load(public_path('receipt.xsl'));

        // Create the XSLT processor
        $proc = new XSLTProcessor();
        $proc->importStylesheet($xsl);

        // Transform the XML
        $html = $proc->transformToXml($xml);

        // Pass the transformed HTML to the view
        return view('carts.receipt', ['html' => $html]);
    }

    
}
