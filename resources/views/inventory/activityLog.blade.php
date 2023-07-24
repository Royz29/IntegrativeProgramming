@extends('inventoryLayout.app')

@section('content')
    <div class="container">
        @if (!empty($html))
            {!! $html !!}
        @elseif (isset($errorMessage))
            <div class="alert alert-danger">{{ $errorMessage }}</div>
        @endif
    </div>
@endsection
