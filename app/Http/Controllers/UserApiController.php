<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;

class UserApiController extends Controller
{
    public function searchById($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json(['user' => [$user]], 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }



    public function searchByIdClient($id)
    {
        $url = "http://127.0.0.1:8000/api/users/id/{$id}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);



        if ($error) {
            echo "cURL Error: " . $error;
        } else {

            return view('userClient', ['user' => json_decode($response, true)]);
        }

        curl_close($ch);
    }

    public function searchByName($name)
    {

        header("Content-Type: application/json");
        $users = User::where('name', 'LIKE', '%' . $name . '%')->get();

        if ($users->isNotEmpty()) {
            return response()->json(['user' => $users], 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }


    public function searchUserByNameClient($name)

    {
        $url = "http://127.0.0.1:8000/api/users/name/{$name}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        if ($error) {
            echo "cURL Error: " . $error;
        } else {

            return view('userClient', ['user' => json_decode($response, true)]);
        }

        curl_close($ch);
    }



    public function response($status, $status_message, $data)
    {

        header("HTTP/1.1 {$status} {$status_message}");
        $response['status'] = $status;
        $response['status_message'] = $status_message;
        $response['data'] = $data;

        $json_response = json_encode($response, JSON_PRETTY_PRINT);
        echo $json_response;
    }

    public function showUserClient()
    {
        return view('userClient');
    }
}
