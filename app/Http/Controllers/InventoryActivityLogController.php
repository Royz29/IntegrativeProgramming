<?php

namespace App\Http\Controllers;

use App\Models\InventoryActivityLog;
use DOMDocument;
use Illuminate\Http\Request;
use XSLTProcessor;
use SimpleXMLElement;
use DOMImplementation;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class InventoryActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (Auth::check() && $user->role == 'admin') {
            $inventoryActivityLogs = InventoryActivityLog::latest()->get();

            $selectedAction = $request->get('actionType', 'all');
            if ($selectedAction !== 'all') {
                // filter logs based on selected action type
                $inventoryActivityLogs = $inventoryActivityLogs->filter(function ($log) use ($selectedAction) {
                    return $log->action === $selectedAction;
                });
            }

            $imp = new DOMImplementation;

            // Creates a DOMDocumentType instance
            $dtd = $imp->createDocumentType('inventoryActivityLogs', '', 'inventoryActivityLogs.dtd');

            // Creates a DOMDocument instance
            $xmlDoc = $imp->createDocument("", "", $dtd);

            // Set other properties
            $xmlDoc->encoding = 'UTF-8';

            //create root element
            $rootElement = $xmlDoc->createElement('inventoryActivityLogs');
            $xmlDoc->appendChild($rootElement);

            //loop through audit logs and create child nodes for each entry
            foreach ($inventoryActivityLogs as $log) {
                $logElement = $xmlDoc->createElement('inventoryActivityLog');
                $logElement->setAttribute('inventory_name', $log->inventory_name);
                $logElement->setAttribute('action', $log->action);
                $logElement->setAttribute('created_at', $log->created_at->format('Y-m-d H:i:s'));

                //create child nodes for old_values and new_values
                $oldValuesElement = $xmlDoc->createElement('old_values');
                $oldValuesElement->appendChild($xmlDoc->createCDATASection(json_encode($log->old_values)));
                $logElement->appendChild($oldValuesElement);

                $newValuesElement = $xmlDoc->createElement('new_values');
                $newValuesElement->appendChild($xmlDoc->createCDATASection(json_encode($log->new_values)));
                $logElement->appendChild($newValuesElement);

                //append log element to root element
                $rootElement->appendChild($logElement);
            }

            //save XML document to file
            $xmlDoc->save('inventoryActivityLogs.xml');

            //load XSLT stylesheet
            $xslDoc = new DOMDocument();
            $xslDoc->load('inventoryActivityLogs.xsl');

            //create XSLT processor and apply stylesheet to XML document
            $xsltProc = new XSLTProcessor();
            $xsltProc->importStylesheet($xslDoc);

            //load XML document and transform it using XSLT
            $xmlData = file_get_contents('inventoryActivityLogs.xml');
            $html = $xsltProc->transformToXML(new SimpleXMLElement($xmlData));

            if ($inventoryActivityLogs->isEmpty()) {
                $errorMessage = 'no record';
                return view('inventory.activityLog', ['html' => $html, 'errorMessage' => $errorMessage])->with('selectedAction', $selectedAction);
            }

            return view('inventory.activityLog', compact('html'))->with('selectedAction', $selectedAction);
        }

        Session::flush();

        Auth::logout();

        return redirect('login')->with('success', 'you are not allowed to access');
    }
}
