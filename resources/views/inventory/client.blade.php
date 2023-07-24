<style>
    h1 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 16px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        text-align: left;
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    th {
        background-color: #4a83ef;
        color: white;
    }

    img {
        max-height: 100px;
        max-width: 100px;
    }
</style>

<h1>REST API Inventory Client Side</h1>

@if(isset($inventory['inventory']))
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Image</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventory['inventory'] as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['description'] }}</td>
                    <td><img src="{{ asset('images/'.$item['image']) }}" alt="{{ $item['name'] }}"></td>
                    <td>{{ $item['category'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ $item['price'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No inventory records found.</p>
@endif
