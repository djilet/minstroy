@extends('layouts.admin')


@section('title')
    Добавить страницу
@endsection


@section('content')
    <section class="box">
        <header class="panel_header">
            <h2 class="title pull-left">Добавить страницу</h2>
        </header>

        <div class="content-body">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-1" data-toggle="tab">Содержание</a></li>
                <li><a href="#tab-2" data-toggle="tab">Изображения</a></li>
                <li><a href="#tab-3" data-toggle="tab">SEO</a></li>
            </ul>

            <form action="{{ route('admin.page.update', $page->id) }}" method="post" name="page-form" id="page-form" enctype="multipart/form-data">
                <div class="tab-content">
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    
                    <div id="tab-1" class="tab-pane active">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" class="required">Заголовок</label><br>
                                <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $page->title) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="active">Активна</label><br>
                                    <input type="hidden" name="active" value="0">
                                    <input type="checkbox" name="active" id="active" value="1" class="iswitch iswitch-md iswitch-primary" {{ old('active', $page->active) ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="template" class="required">Шаблон страницы</label><br>
                                    <select name="template" id="template" class="form-control">
                                        <option value="">Основной шаблон</option>
                                        @foreach($templates as $tpl => $title)
                                            <option value="{{ $tpl }}" {{ $tpl == $page->template ? 'checked' : '' }}>{{ $title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="form-group form-inline">
                                <label for="menu_id" class="required">Родитель</label><br>
                                <select name="menu_id" id="menu_id" class="form-control">
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" {{ $menu->id == $page->menu_id ? 'checked' : '' }}>{{ $menu->title }}</option>
                                    @endforeach
                                </select>

                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="">...</option>
                                    @foreach($menus as $menu)
                                        @include('admin.page._select', [
                                            'pages' => $menu->pages->toTree(),
                                            'currentPage' => $page
                                        ])
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group form-inline">
                                <label for="slug" class="required">URL страницы</label><br>
                                {{ url('/') }}/<span id="ParentURL">{{ $page->pathSlug(true) }}</span>
                                <input type="text" class="form-control" name="slug" id="slug" value="{{ old('slug', $page->slug) }}">.html
                            </div>

                            <div class="form-group">
                                <textarea class="ckeditor" rows="20" name="content" id="content">{{ old('content', $page->content) }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div id="tab-2" class="tab-pane">
                        <div class="col-md-6">


                        </div>
                    </div>
                    

                    <div id="tab-3" class="tab-pane">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title_h1">Заголовок h1</label><br>
                                <input class="form-control" type="text" name="title_h1" id="title_h1" value="{{ old('title_h1', $page->title_h1) }}">
                            </div>
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label><br>
                                <input class="form-control" type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title) }}">
                            </div>
                            <div class="form-group">
                                <label for="meta_keywords">Meta Keywords</label><br>
                                <textarea class="form-control" name="meta_keywords" id="meta_keywords" rows="5" cols="80">{{ old('meta_keywords', $page->meta_keywords) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="meta_description">Meta Description</label><br>
                                <textarea class="form-control" name="meta_description" id="meta_description" rows="5" cols="80">{{ old('meta_description', $page->meta_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                
                
                <div class="top15">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn btn-success btn-icon right15"><i class="fa fa-save"></i>Сохранить</button>
                    <a class="btn btn-icon" href="{{ route('admin.page.index') }}"><i class="fa fa-ban"></i>Отмена</a>
                </div>
            </form>
        </div>

    </section>
@endsection

@push('footer.scripts')
    <script type="text/javascript" src="{{ asset('assets/admin') }}/plugins/ckeditor/ckeditor.js"></script>

    <script>
        createCKEditor('content');

        $('select#menu_id').change(function(e, u) {
            var menuId = $(this).find('option:selected').val();
            $('select#parent_id option[data-menu]').addClass('hidden');
            $('select#parent_id option[data-menu="' + menuId + '"]').removeClass('hidden');
            
            if (u !== true) {
                $('select#parent_id option:first').prop('selected',true).change();
            }
        }).trigger('change', true);
        
        $('select#parent_id').change(function() {
            var path = $(this).find('option:selected').data('path');
            $('#ParentURL').text(path || '');
        });
    </script>
@endpush