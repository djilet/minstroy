@extends('layouts.admin')

@section('title')
    @lang('admin/dashboard.title')
@endsection

@section('content')

    <section class="box">
        <header class="panel_header">
            <h2 class="title pull-left">
                @lang('admin/dashboard.title')
            </h2>
        </header>
        
        <div class="content-body">
            @include('admin.menu.index', ['menus' => $menus])
        </div>
    </section>

@endsection