<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
</head>

<style>
    table,
    th,
    td {
        border: 1px solid;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    body {
        background-color: white;
    }

    nav {
        background-color: #006FFF;
        width: 100%;
        height: 90px;
    }

    ul {
        padding: 50px, 0px, 100px, 0px;
        margin-top: 10px;
        list-style: none;
        list-style-position: outside;
    }

    li {
        display: inline-block;
        text-align: center;
        color: #222222;
        margin: 15px 0px 5px 20px;
        float: right;
    }

    a {
        text-decoration: none;
        color: white;
        display: block;
        padding: 8px;
        font-size: 40px
    }

    img {
        width: 75px;
        height: 75px;
    }

    input {
        width: 100px;
        transition: 1s;
        -webkit-transition: 1s;
        box-sizing: border-box;
        outline: none;
    }

    input:focus {
        border: 3px solid black;
        background-color: lightgrey;
    }

    .alert {
        font-size: 50px;
        color: lightblue;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        $('#category').change(function() {
            $('.filter-form').submit();
        });
    });
</script>

<script>
    function validateQuantity() {
        var quantityInput = document.getElementsByName('quantity')[0];
        var quantity = quantityInput.value;
        if (quantity <= 0) {
            alert("Quantity cannot be negative or zero!");
            quantityInput.focus();
            return false;
        }
        return true;
    }
</script>


<body>

    <nav>
        <ul>
            <li><a href="{{ route('cart.index') }}">Cart</a></li>
            @if(Auth::check() && Auth::user()->role == 'member')
            <li><a href="{{ route('dashboard') }}">Profile</a></li>
            @else
            <li><a href="{{ route('adminPanel') }}">Profile</a></li>
            @endif
        </ul>
    </nav>


    <h1>Product Page</h1>

    <form action="{{ route('products.product') }}" method="GET" class="filter-form">
        <label>Select by Category</label>
        <select name="category" id="category">
            <option value="All" {{ Request::input('category') == 'All' ? 'selected' : '' }}>All</option>
            <option value="Smartphone" {{ Request::input('category') == 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
            <option value="Laptop" {{ Request::input('category') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
            <option value="Smart TV" {{ Request::input('category') == 'Smart TV' ? 'selected' : '' }}>Smart TV</option>
        </select>
        <label>Search by Name</label>
        <input type="text" name="name" value="{{ Request::input('name') }}">
        <button type="submit">Search</button>
    </form>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    <?php session()->forget('variableName'); ?>
    @endif

    <table id="product-table">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Image</th>
        </tr>

        @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->category }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->price }}</td>
            <td><img src="{{ asset('images/' . $product->image) }}"></td>
            </td>
            <td>
                <form method="POST" action="{{ route('cart.add', ['id' => $product->id]) }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="quantity" value="1" min="1">
                    <button type="submit" onclick="return validateQuantity()">Add</button>
                </form>
            </td>

        </tr>
        @endforeach
    </table>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

</body>

</html>