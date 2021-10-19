@extends('layouts.admin')

@section('title')
    Просмотр профиля :: {{ $admin->name }}
@endsection

@section('content')

    <section class="box">
        <header class="panel_header">
            <h2 class="title pull-left">Просмотр профиля</h2>
        </header>

        <div class="content-body">
            
            <div class="row">
            
                <div class="col-md-6">
                    <section class="box">
                        <header class="panel_header">
                            <h2 class="title pull-left">Персональная информация</h2>
                        </header>

                        <div class="content-body">
                            <div class="form-group" id="UserImage-box">
                                <label>Картинка пользователя</label><br>
                                <div id="UserImage-img" style="display:none;">
                                    <img src="" alt="" style="max-width:260px;" class="input-img">
                                </div>
                                
                                <div class="clearfix"></div>
                            </div>


                            <div class="form-group">
                                <label>Имя</label><br> {{ $admin->name }}
                            </div>

                            <div class="form-group">
                                <label>Телефон</label><br> {{ $admin->phone }}
                            </div>

                            <div class="form-group">
                                <label>Эл. почта/Логин</label><br> {{ $admin->email }}
                            </div>
                            
                            <div class="form-group">
                                <label>Роль</label><br> @lang('admin/role.'.$admin->role)
                            </div>
                        </div>
                    </section>
                </div>
                    
                <div class="col-md-6">
                    <section class="box">
                        <header class="panel_header">
                            <h2 class="title pull-left">Информация</h2>
                        </header>
                        <div class="content-body">
                            <div class="form-group">
                                <label>Зарегистрирован</label><br>
                                {{ $admin->created_at }}
                            </div>

                            <div class="form-group">
                                <label>Дата последнего входа</label><br>
                                {{ $admin->login_at }}
                            </div>

                            <div class="form-group">
                                <label>IP адрес последнего входа</label><br>
                                {{ $admin->login_ip }}
                            </div>
                        </div>
                    </section>
                </div>
                
                <div class="clearfix"></div>
                <a class="btn btn-icon" href="{{ route("admin.profile.index") }}"><i class="fa fa-ban"></i>Назад</a>

            </div>
        </div>
    </section>

@endsection