@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <flights :tz="'{{ $tz }}'"></flights>
        </div>
    </div>
</div>
@endsection
