@extends('inventoryLayout.app')
@section('content')
<main class="container">
    <section>
        <button onclick="window.location.href='adminPanel'">Back</button>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/inventory.css') }}">
        <div class="titlebar">
            <h1>Inventories</h1>
            <a href="{{ route('inventory.create') }}" class='btn-link'>Add Inventory</a>
        </div>
        @if ($message = Session::get('success'))
        <script type="text/javascript">
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: '{{ $message }}'
            })
        </script>
        @endif
        <div class="table">
            <div class="table-filter">
                <div>
                    <ul class="table-filter-list">
                        <li>
                            <p>
                                <a href="{{ route('inventory.activityLog') }}" class="table-filter-link link-active">View Inventory Activity Log</a>
                            </p>

                        </li>
                    </ul>
                </div>
            </div>
            <form method="GET" action="{{ route('inventory.index') }}" accept-charset="UTF-8" role="search">
                <div class="table-search">
                    <div>
                        <button class="search-select">
                            Search Inventory
                        </button>
                        <span class="search-select-arrow">
                            <i class="fas fa-caret-down"></i>
                        </span>
                    </div>
                    <div class="relative">
                        <input class="search-input" type="text" name="search" placeholder="Search by name or category" name="search" value="{{ request('search') }}">
                    </div>
                </div>
            </form>
            <div class="table-product-head">
                <p>Image</p>
                <p>Name</p>
                <p>Category</p>
                <p>Quantity</p>
                <p>Price</p>
                <p>Actions</p>
            </div>
            <div class="table-product-body">
                @if (isset($inventory))
                    @foreach ($inventory as $inventory)
                        <img src="{{ asset('images/' . $inventory->image) }}" />
                        <p>{{ $inventory->name }}</p>
                        <p>{{ $inventory->category }}</p>
                        <p>{{ $inventory->quantity }}</p>
                        <p>{{ $inventory->price }}</p>
                        <div class="actions-wrapper">
                            <a href="{{ route('inventory.edit', Crypt::encrypt($inventory->id)) }}" class="btn btn-success edit-btn" style="padding-top:4px; padding-bottom:4px">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form method="post" action="{{ route('inventory.delete', Crypt::encrypt($inventory->id)) }}">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger" style="margin-top:0" onclick="deleteConfirm(event)">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p>Inventory Not Found</p>
                @endif
            </div>
            <div class="table-paginate">
                <div class="pagination">
                    <a class="active-page">Page 1 of 1</a>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    window.deleteConfirm = function(e) {
        e.preventDefault();
        var form = e.target.form;
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    }
</script>
@endsection
