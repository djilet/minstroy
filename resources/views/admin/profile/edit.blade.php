@extends('layouts.admin')

@section('title')
    Редактировать профиль :: {{ $admin->name }}
@endsection

@section('content')

    <section class="box">
        <header class="panel_header">
            <h2 class="title pull-left">Редактировать пользователя</h2>
        </header>
        
        <div class="content-body">
            <form action="{{ route('admin.profile.update', $admin->id) }}" method="post" class="nform" enctype="multipart/form-data">
                <div class="col-md-6">
                    <section class="box">
                        <header class="panel_header">
                            <h2 class="title pull-left">Персональная информация</h2>
                        </header>
                        
                        <div class="content-body">
                            <div class="form-group" id="UserImage-box">
                                <label for="UserImage">Картинка пользователя</label><br>
                                <div id="UserImage-img" style="display:none;">
                                    <img src="" alt="" style="max-width:260px;" class="input-img">
                                </div>
                                <a id="UserImage-btn" class="btn btn-primary btn-icon change-file" title="Добавить">
                                    <i class="fa fa-image"></i>
                                    Добавить
                                </a> 
                                <a id="UserImage-del" class="btn btn-danger btn-icon delete-file" imagename="UserImage" itemid="1" removeaction="RemoveUserImage" ajaxpath="ajax.php" pageid="0" style="display:none;">
                                    <i class="fa fa-remove"></i>
                                    Удалить
                                </a>
                                <div class="clearfix"></div>
                                <div class="hidden" id="UserImage-file">
                                    <input name="UserImage" id="UserImage" type="file" size="1">
                                </div>
                                <input type="hidden" name="SavedUserImage" id="SavedUserImage" value="">
                            </div>


                            <div class="form-group{{ $errors->has('name') ? " has-error" : '' }}">
                                <label for="name" class="required">Имя</label><br>
                                <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" autocomplete="off">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="form-group{{ $errors->has('phone') ? " has-error" : '' }}">
                                <label for="phone">Телефон</label><br>
                                <input class="form-control" type="text" name="phone" id="phone" value="{{ old('phone', $admin->phone) }}" autocomplete="off">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </section>
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
                <div class="col-md-6">
                    <section class="box">
                        <header class="panel_header">
                            <h2 class="title pull-left">Авторизация</h2>
                        </header>
                        <div class="content-body">
                            <div class="form-group{{ $errors->has('email') ? " has-error" : '' }}">
                                <label for="email" class="required">Эл. почта/Логин</label><br>
                                <input class="form-control" type="text" name="email" id="email" value="{{ old('email', $admin->email) }}" autocomplete="off">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="form-group{{ $errors->has('password_old') ? " has-error" : '' }}">
                                <label for="password_old">Старый пароль</label> 
                                [<a href="#" onclick="return false;" title="Чтобы установить новый пароль, нужно знать старый">?</a>]<br>
                                <input class="form-control" type="password" name="password_old" id="password_old" autocomplete="off">

                                @if ($errors->has('password_old'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_old') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="form-group{{ $errors->has('password') ? " has-error" : '' }}">
                                <label for="password">Новый пароль</label><br>
                                <input class="form-control" type="password" name="password" id="password" autocomplete="off">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation">Повторите пароль</label><br>
                                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" autocomplete="off">
                            </div>
                            
                            <div class="form-group">
                                <label class="required">Роль</label><br>
                                @can('changeRole', $admin)
                                    <select name="role" class="form-control">
                                        @foreach($adminRole as $role)
                                            <option value="{{ $role }}"{{ $admin->role == $role ? ' selected' : '' }}>@lang('admin/role.'.$role)</option>
                                        @endforeach
                                    </select>
                                @else
                                    @lang('admin/role.'.$admin->role)
                                @endcan
                            </div>
                            
                        </div>
                    </section>
                </div>

                <div class="clearfix"></div>

                <div>
                    @csrf
                    @method('put')
                    <button type="submit" class="btn btn-success btn-icon left15 right15"><i class="fa fa-save"></i>Сохранить</button>
                    <a class="btn btn-icon" href="{{ url()->previous() ?? route("admin.profiles") }}"><i class="fa fa-ban"></i>Отмена</a>
                </div>
            </form>
        </div>
    </section>
    
@endsection