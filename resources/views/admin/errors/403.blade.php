@extends('layouts.admin')

@section('content')
    <section class="box">
        <header class="panel_header">
            <h2 class="title pull-left">@lang('admin/error.403.title')</h2>
        </header>

        <div class="content-body" style="text-align: center;">

            <img src="{{ asset('assets/admin/images/403.png') }}" alt="403 Access denied">

        </div>
    </section>
@endsection