@extends('main')

@section('content')

@if($message = Session::get('success'))

<div class="alert alert-info">
    {{ $message }}
</div>

@endif

@if($message = Session::get('error'))

<div class="alert alert-info">
    {{ $message }}
</div>

@endif

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Member Details</h4>
                    <label for="image"></label>
                    <div>
                        @if(Auth::user()->image)
                        <img src="{{ asset('images/' . Auth::user()->image) }}" alt="{{ Auth::user()->name }}'s avatar" style="max-width: 200px; height: auto;">
                        @else
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Default avatar" style="max-width: 200px; height: auto;">
                        @endif
                    </div>
                    <form method="POST" action="/update/{{ Auth::user()->id }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Update Details</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.delete', encrypt(Auth::user()->id)) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Recent Activity</h4>
                    <ul>
                        <li>User {{ Auth::user()->name }} logged in</li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Website Metrics</h4>
                    <p>Registered Users: 10,000</p>
                    <p>Daily Page Views: 50,000</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Upload your Photo!</h4>
                    <form action="/uploadImage" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="image">Select an image:</label>
                            <input type="file" class="form-control" id="image" name="image">
                            @if($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                            @endif
                        </div>
                        </br>
                        <button type="submit" class="btn btn-primary">Upload Image</button>
                    </form>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Go shopping</h4>
                    </div>
                    <a href="{{ route('products.product') }}" class="btn btn-primary btn-block">Go</a>
                    <br>
                </div>
            </div>
        </div>
    </div>

    @endsection('content')