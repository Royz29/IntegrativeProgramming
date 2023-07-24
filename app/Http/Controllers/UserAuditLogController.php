<?php

namespace App\Http\Controllers;

use App\Models\UserAuditLog;
use DOMDocument;
use DOMImplementation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use XSLTProcessor;
use SimpleXMLElement;

class UserAuditLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (Auth::check() && $user->role == 'admin') {

            $userAuditLogs = UserAuditLog::latest()->get();

            $selectedAction = $request->get('actionType', 'all');
            if ($selectedAction !== 'all') {
                // filter logs based on selected action type
                $userAuditLogs = $userAuditLogs->filter(function ($log) use ($selectedAction) {
                    return $log->action === $selectedAction;
                });
            }


            $imp = new DOMImplementation;

            // Creates a DOMDocumentType instance
            $dtd = $imp->createDocumentType('userAuditLogs', '', 'userAuditLogs.dtd');

            // Creates a DOMDocument instance
            $xmlDoc = $imp->createDocument("", "", $dtd);

            // Set other properties
            $xmlDoc->encoding = 'UTF-8';


            //create root element
            $rootElement = $xmlDoc->createElement('userAuditLogs');
            $xmlDoc->appendChild($rootElement);

            //loop through audit logs and create child nodes for each entry
            foreach ($userAuditLogs as $log) {
                $logElement = $xmlDoc->createElement('userAuditLog');
                $logElement->setAttribute('id', $log->id);
                $logElement->setAttribute('action', $log->action);
                $logElement->setAttribute('user_agent', $log->user_agent);
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
            $xmlDoc->save('userAuditLogs.xml');

            //load XSLT stylesheet
            $xslDoc = new DOMDocument();
            $xslDoc->load('userAuditLogs.xsl');

            //create XSLT processor and apply stylesheet to XML document
            $xsltProc = new XSLTProcessor();
            $xsltProc->importStylesheet($xslDoc);

            //load XML document and transform it using XSLT
            $xmlData = file_get_contents('userAuditLogs.xml');
            $html = $xsltProc->transformToXML(new SimpleXMLElement($xmlData));

            if ($userAuditLogs->isEmpty()) {
                $errorMessage = 'no record';
                return view('user_audit_logs', ['html' => $html, 'errorMessage' => $errorMessage])->with('selectedAction', $selectedAction);
            }

            return view('user_audit_logs', compact('html'))->with('selectedAction', $selectedAction);
        }

        Session::flush();

        Auth::logout();

        return redirect('login')->with('success', 'you are not allowed to access');
    }
}
