@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Welcome Back {{ Auth::user()->name }} </h1>
        </div>
    </div>

    <!-- ... Your content goes here ... -->

</div>

@endsection
