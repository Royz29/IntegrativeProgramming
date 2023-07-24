@extends('inventoryLayout.app')
@section('content')
<main class="container">
    <section>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/inventory.css') }}">
        <button onclick="window.location.href='../inventory'">Back</button>
        <form method="post" action="{{ route('inventory.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="titlebar">
                <h1>Add Inventory</h1>
            </div>
            @if($errors->any)
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="card">
                <div>
                    <label>Name</label>
                    <input type="text" name="name">
                    <label>Description</label>
                    <textarea cols="10" rows="5" name="description"></textarea>
                    <label>Add Image</label>
                    <img src="" alt="" class="img-product" id="file-preview" />
                    <input type="file" name="image" accept="image/*" onchange="showFile(event)">
                </div>
                <div>
                    <label>Category</label>
                    <select name="category">
                        @foreach (json_decode('{"Smartphone":"Smartphone","Laptop":"Laptop",
                        "Smart TV":"Smart TV"}', true) as $optionKey => $optionValue)
                        <option value="{{ $optionKey }}">{{ $optionValue }}</option>
                        @endforeach
                    </select>
                    <hr>
                    <label>Quantity</label>
                    <input type="number" name="quantity" min="0" max="9999">
                    <hr>
                    <label>Price</label>
                    <input type="number" name="price" min="1" max="99999">
                    <button>Save</button>
                </div>
            </div>
        </form>
    </section>
</main>
<script>
    function showFile(event) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function() {
            var dataURL = reader.result;
            var output = document.getElementById('file-preview');
            output.src = dataURL;
        }
        reader.readAsDataURL(input.files[0]);
    }
</script>
@endsection