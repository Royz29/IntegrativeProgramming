<!-- resources/views/user/client.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>User Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .user-card {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .user-card img {
            max-width: 200px;
            height: auto;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .user-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .user-email {
            color: #666;
        }

        .no-user {
            text-align: center;
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>
    <h1>REST API Client Side User Search Results</h1>

    @if (isset($user['user']))
    @foreach ($user['user'] as $userData)
    <div class="user-card">
        <p> Profile Picture</p>
        <p><img src="{{ asset('images/'.$userData['image']) }}" alt="{{ $userData['name'] }} " style="max-width: 200px; height: auto;"></p>
        <p class="user-name">username :{{ $userData['name'] }}</p>
        <p class="user-email">email :{{ $userData['email'] }}</p>

    </div>
    @endforeach


    @else
    <p>No user found.</p>
    @endif
</body>

</html>