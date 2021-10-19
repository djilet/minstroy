@extends('layouts.admin')

@section('content')
    <section class="box">
        <header class="panel_header">
            <h2 class="title pull-left">@lang('admin/error.404.title')</h2>
        </header>
        
        <div class="content-body" style="text-align: center;">

            <img src="{{ asset('assets/admin/images/404.gif') }}" alt="404 not found">
            
        </div>
    </section>
@endsection