<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Console\Commands\DeleteAccountCommand;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;



class UserController extends Controller
{
    function index()
    {
        return view('login');
    }

    function registration()
    {
        return view('registration');
    }

    function adminPanel()
    {
        $user = Auth::user();
        if (Auth::check() && $user->role == 'admin') {
            return view('adminPanel');
        }

        Session::flush();

        Auth::logout();

        return redirect('login')->with('success', 'you are not allowed to access');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);


        // Check if the email entered by the user already exists in the database
        $existingUser = User::where('email', $validatedData['email'])
            ->where('id', '!=', $id)
            ->first();
        if ($existingUser) {
            return back()->with('error', 'The email address you entered already exists in our records.');
        }

        // Check if any input data is different from current details
        if ($validatedData['name'] === $user->name && $validatedData['email'] === $user->email) {
            return back()->with('error', 'No changes were made to the user details');
        }

        // Update the user's details
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }
        $user->save();

        return back()->with('success', 'User details updated successfully');
    }

    function validate_registration(Request $request)
    {
        $request->validate([
            'name'         =>   'required|string|min:6|max:255',
            'email' => 'required|email|unique:users|regex:/^[^@]+@[^@]+\.[^@]+$/',
            'password' => 'required|string|min:6|max:255|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/|confirmed',
            'password_confirmation' => 'required',
        ]);

        $data = $request->all();

        User::create([
            'name'  =>  $data['name'],
            'email' =>  $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        return redirect('login')->with('success', 'Registration Completed, now you can login');
    }

    function validate_login(Request $request)
    {
        $request->validate([
            'email' =>  'required',
            'password'  =>  'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check the user's role
            if ($user->role == 'admin') {
                return redirect('adminPanel');
            } else {
                return redirect('dashboard');
            }
        }

        return redirect('login')->with('success', 'Login details are not valid');
    }


    function dashboard()
    {
        $user = Auth::user();
        if (Auth::check() && $user->role == 'member') {
            return view('dashboard');
        }

        Session::flush();

        Auth::logout();

        return redirect('login')->with('success', 'you are not allowed to access');
    }

    function logout()
    {
        Session::flush();

        Auth::logout();

        return Redirect('login');
    }

    private $deleteAccountCommand;

    public function __construct(DeleteAccountCommand $deleteAccountCommand)
    {
        $this->deleteAccountCommand = $deleteAccountCommand;
    }

    public function delete($id)
    {
        try {
            $decryptedId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        // Call the command and pass the user ID as an argument
        $this->deleteAccountCommand->setArguments(['id' => $decryptedId])->handle();

        // Log out the user
        auth()->logout();

        // Redirect to the login page with a success message
        return redirect()->route('login')->with('success', 'Your account has been deleted.');
    }

    public function showAllUser()
    {

        $users = User::all();

        if ($users) {
            return response()->json(['user' => $users], 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function showAllUserClient()
    {

        $url = "http://127.0.0.1:8000/api/users/";
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

    public function uploadImage(Request $request)
    {
        $user = User::find(Auth::id());
        $userRole = Auth::user();

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // validate that the file is an image, is not larger than 2MB, and has one of the allowed MIME types
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate a unique filename for the uploaded file
            $file_name = uniqid() . '_' . $file->getClientOriginalName();

            // Move the uploaded file to the public/images directory
            $file->move(public_path('images'), $file_name);

            // Update the user's image field
            $user->image = $file_name;
            $user->save();

            if ($userRole->role == 'member') {
                return redirect('/dashboard')->with('success', 'Image uploaded successfully.');
            } else {
                return redirect('/adminPanel')->with('success', 'Image uploaded successfully.');
            }
        }

        if ($userRole->role == 'member') {
            return redirect('/dashboard')->with('error', 'Please select an image to upload.');
        } else {
            return redirect('/adminPanel')->with('error', 'Please select an image to upload.');
        }
    }
}
