<!DOCTYPE html>
<html>

<head>
    <title>Cart</title>
</head>
<style>
    table,
    th,
    td {
        border: 1px solid;
        text-align: center;
    }

    table {
        width: 700px;
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

    .size {
        width: 50px;
    }

    .shoppingBtn {
        font-size: 20px;
        color: black;
        border: 1px solid black;
        width: 170px;
    }

    .alert {
        font-size: 50px;
        color: red;
    }

    .alerts {
        font-size: 15px;
        color: red;
    }
</style>

<body>

    <nav>
        <ul>
            <li><a href="{{ route('products.product') }}">Product</a></li>

            @if(Auth::check() && Auth::user()->role == 'admin')
            <li><a href="{{ route('adminPanel') }}">Profile</a></li>
            @else
            <li><a href="{{ route('dashboard') }}">Profile</a></li>
            @endif
        </ul>
        </ul>

    </nav>
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    <?php session()->forget('variableName'); ?>
    @endif
    <h1>Cart</h1>
    @if($carts->count() > 0)
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carts as $cart)
            <tr>
                <td class="size"><img src="{{ asset('images/' . $cart->image) }}" style="text-align: center;"></td>
                <td>{{ $cart->name }}</td>
                <td>
                    <form action="{{ route('cart.updateQuantity', $cart) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1">
                        <button type="submit">Update</button>
                    </form>

                </td>
                <td>{{ $cart->total_quantity }}</td>
                <td>{{ $cart->total_price }}</td>
                <td class="size">
                    <form action="{{ route('cart.destroy', $cart) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Remove</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @if(session('success'))
            <div class="alerts alert-danger">
                {{ session('success') }}
            </div>
            <?php session()->forget('variableName'); ?>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="right">Total Quantity:</td>
                <td>{{ $carts->sum('total_quantity') }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" align="right">Total Price:</td>
                <td>{{ $carts->sum('total_price') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @else
    <p>No items in cart.</p>
    @endif
    <a class="shoppingBtn" href="{{ route('products.product') }}">Continue Shopping</a>
    <form action="{{ route('cart.checkout') }}" method="POST">
        @csrf
        <button type="submit">Checkout</button>
    </form>

</body>

</html>