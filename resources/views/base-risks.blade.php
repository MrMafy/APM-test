@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-5">Реестр рисков и возможностей</h1>

        @include('base-riskPage')

        @include('base-possibilities')

    </div>
@endsection

