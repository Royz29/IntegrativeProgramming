<?php

namespace App\Http\Controllers;

class InventoryClientController extends Controller
{
    public function getAllInventory(){
        // The URL of the API endpoint to call
        $url = "http://127.0.0.1:8000/api/inventory";

        // Initialize a new cURL session
        $ch = curl_init();

        // Set the options for the cURL session
        curl_setopt($ch, CURLOPT_URL, $url); // Set the URL to call
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string

        // Execute the cURL request and save the response
        $response = curl_exec($ch);

        // Get any errors that occurred during the cURL request
        $error = curl_error($ch);

        // Decode the json response
        $inventory = json_decode($response, true);

        if($error){
            echo "cURL Error: " . $error;
        }else{
            return view('inventory.client',['inventory'=>$inventory]);
        }

        // Close the cURL session
        curl_close($ch);
    }

    public function searchByName($name){
        // Replace spaces in the name parameter with underscores
        $name = str_replace(' ', '_', $name);

        // The URL of the API endpoint to call
        $url = "http://127.0.0.1:8000/api/inventory/name/{$name}";

        // Initialize a new cURL session
        $ch = curl_init();

        // Set the options for the cURL session
        curl_setopt($ch, CURLOPT_URL, $url); // Set the URL to call
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string

        // Execute the cURL request and save the response
        $response = curl_exec($ch);

        // Get any errors that occurred during the cURL request
        $error = curl_error($ch);

        // Decode the json response
        $inventory = json_decode($response, true);

        if($error){
            echo "cURL Error: " . $error;
            return response("An error occurred: " . $error, 500);
        }else{
            return view('inventory.client', ['inventory' => $inventory]);
        }

        // Close the cURL session
        curl_close($ch);
    }

    public function searchByCategory($category){
        // The URL of the API endpoint to call
        $url = "http://127.0.0.1:8000/api/inventory/category/{$category}";

        // Initialize a new cURL session
        $ch = curl_init();

        // Set the options for the cURL session
        curl_setopt($ch, CURLOPT_URL, $url); // Set the URL to call
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string

        // Execute the cURL request and save the response
        $response = curl_exec($ch);

        // Get any errors that occurred during the cURL request
        $error = curl_error($ch);

        // Decode the json response
        $inventory = json_decode($response, true);

        if($error){
            echo "cURL Error: " . $error;
        }else{
            return view('inventory.client',['inventory'=>$inventory]);
        }

        // Close the cURL session
        curl_close($ch);
    }
}
