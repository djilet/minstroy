@extends('layouts.admin')

@section('title')
    Администраторы
@endsection

@section('content')

    <section class="box">
        <header class="panel_header">
            <h2 class="title pull-left">Администраторы</h2>
        </header>
        
        <div class="content-body">
            
            @if(count($admins) > 0)

                <div class="col-md-6 no-padding form-group">
                    <form action="{{ route('admin.profile.index') }}" method="get">
                        <div class="input-group primary">
                            <span class="input-group-addon" onclick="$(this).closest('form').submit();">
                                <span class="arrow"></span>
                                <i class="fa fa-search"></i>
                            </span>
                            <input type="text" name="q" class="form-control" value="" placeholder="Поиск...">
                        </div>
                    </form>
                </div>
        
                <div class="col-md-6 no-padding form-group">
                    <ul class="pagination pagination pull-right">
                        {{ $admins->links() }}
                    </ul>
                    <div class="clearfix"></div>
                </div>
        
                <div class="clearfix"></div>
        
                @can('create', App\Models\Admin::class)
                    <div class="form-group">
                        <a href="{{ route('admin.profile.create') }}" class="btn btn-success btn-icon">
                            <i class="fa fa-plus"></i>
                            Добавить
                        </a>
                    </div>
                @endcan
        
                <div>
                    
                    <p>{{ $admins->firstItem() }}-{{ $admins->lastItem() }} из {{ $admins->total() }}</p>
                    <table class="table" id="admin-list">
                        
                        <thead>
                            <tr>
                                <th width="25">
                                    @can('deleting', App\Models\Admin::class)
                                        <input type="checkbox" class="icheck-minimal-green check-all" InputName="UserIDs[]" />
                                    @endcan
                                </th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Роль</th>
                                <th>Зарегистрирован</th>
                                <th width="40">&nbsp;</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                        
                            @foreach($admins as $admin)
                                <tr>
                                    <td>
                                        @can('delete', $admin)
                                            <input type="checkbox" class="icheck-minimal-green" name="UserIDs[]" value="{{ $admin->id }}">
                                        @endcan
                                    </td>
                                    <td>
                                        @can('update', $admin)
                                            <a href="{{ route('admin.profile.edit', $admin->id) }}" title="Кликните для редактирования">
                                                {{ $admin->name }}
                                            </a>
                                        @elsecan('view', $admin)
                                            <a href="{{ route('admin.profile.show', $admin->id) }}" title="Кликните для просмотра">
                                                {{ $admin->name }}
                                            </a>
                                        @else
                                            {{ $admin->name }}
                                        @endcan
                                    </td>
                                    <td><a href="{{ $admin->email }}">{{ $admin->email }}</a></td>
                                    <td>@lang('admin/role.'.$admin->role)</td>
                                    <td>{{ $admin->created_at }}</td>
                                    <td>
                                        @can('delete', $admin)
                                            <form action="{{ route('admin.profile.destroy', $admin->id) }}" method="post" class="remove-confirmed">
                                                @csrf
                                                {{ method_field('delete') }}
                                                <button type="button" class="fa fa-close delete" data-title="{{ $admin->name }}"></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        
                        </tbody>
                    </table>
                    
                    <div class="col-md-6 no-padding">
                        @can('deleting', App\Models\Admin::class)
                            <form action="{{ route('admin.profile.destroy.multi') }}" method="post" data-list="#admin-list" data-item="UserIDs[]" class="multiple-remove">

                                @csrf
                                {{ method_field('delete') }}

                                <button class="btn btn-danger btn-icon" type="button">
                                    <i class="fa fa-remove"></i>
                                    Удалить выбранных
                                </button>
                                
                            </form>
                        @endcan
                    </div>
                    <div class="col-md-6 no-padding">
                        <ul class="pagination pagination pull-right">
                            {{ $admins->links() }}
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                
            @else
                @lang('admin/app.empty_list')
            @endif
            
        </div>
    </section>
    
@endsection