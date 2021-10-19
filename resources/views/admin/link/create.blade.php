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
            </ul>

            <form action="{{ route('admin.link.store') }}" method="post" name="page-form" id="page-form" enctype="multipart/form-data">
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
                                <label for="Title" class="required">Заголовок</label><br />
                                <input class="form-control" type="text" name="title" id="Title" value="{{ old('title') }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="Active">Активна</label><br />
                                    <input type="hidden" name="active" value="0">
                                    <input type="checkbox" name="active" id="Active" value="1" class="iswitch iswitch-md iswitch-primary"{{ old('active', 1) == 1 ? ' checked' : '' }} />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="col-md-6">
                            <div class="form-group form-inline">
                                <label for="menu_id" class="required">Родитель</label><br>
                                <select name="menu_id" id="menu_id" class="form-control">
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}"{{ old('menu_id', $openMenuId) == $menu->id ? ' selected' : '' }}>{{ $menu->title }}</option>
                                    @endforeach
                                </select>

                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="">...</option>
                                    @foreach($menus as $menu)
                                        @include('admin.page._select', [
                                            'pages' => $menu->pages->toTree(),
                                            'parent_id' => old('parent_id', 0)
                                        ])
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="type" class="required">Тип ссылки</label><br />
                                <select name="type" id="type" onchange="ChangeLinkType(this);" class="form-control">
                                    <option value="url">URL</option>
                                    <option value="email">Эл. почта</option>
                                    <option value="internal">Страница вашего сайта</option>
                                </select>
                            </div>
                            
                            <div id="divLinkTypeURL" style="display:none;" class="form-group">
                                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                                    <tr>
                                        <td width="25%">
                                            <label for="cmbLinkProtocol">Протокол</label><br />
                                            <select id="cmbLinkProtocol" class="form-control">
                                                <option value="http://">http://</option>
                                                <option value="https://">https://</option>
                                                <option value="ftp://">ftp://</option>
                                                <option value="news://">news://</option>
                                                <option value="">&lt;другое&gt;</option>
                                            </select>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td width="75%">
                                            <label for="txtUrl" class="required">URL</label><br />
                                            <input type="text" id="txtUrl" class="form-control" onkeyup="OnUrlChange();" onchange="OnUrlChange();" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="divLinkTypeEmail" style="display:none;">
                                <div class="form-group">
                                    <label for="txtEmailAddress" class="required">Адрес эл. почты</label><br />
                                    <input type="text" id="txtEmailAddress" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label for="txtEmailSubject">Заголовок сообщения</label><br />
                                    <input type="text" id="txtEmailSubject" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label for="txtEmailBody">Тело сообщения</label><br />
                                    <textarea id="txtEmailBody" class="form-control" rows="4" cols="40"></textarea>
                                </div>
                            </div>
                            <div id="divLinkTypeInternalPage" style="display:none;" class="form-group">
                                <label for="cmbLinkInternalPage" class="required">Выберите страницу вашего сайта</label><br />
                                <select id="cmbLinkInternalPage" class="form-control">
                                    <option>...</option>
                                    @foreach($pages as $page)
                                        <option value="{{ $page->full_slug }}">{{ $page->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="divLinkTarget">
                                <div class="form-group">
                                    <label for="cmbTarget">Цель</label><br />
                                    <select id="cmbTarget" onchange="SetTarget(this.value);" class="form-control">
                                        <option value="" selected="selected">&lt;не определено&gt;</option>
                                        <option value="frame">&lt;фрейм&gt;</option>
                                        <option value="popup">&lt;всплывающее окно&gt;</option>
                                        <option value="_blank">Новое окно (_blank)</option>
                                        <option value="_top">Самое верхнее окно (_top)</option>
                                        <option value="_self">То же окно (_self)</option>
                                        <option value="_parent">Родительское окно (_parent)</option>
                                    </select>
                                </div>
                                <div id="tdTargetFrame" style="display:none" class="form-group">
                                    <label for="txtTargetFrame">Имя целевого фрейма</label><br />
                                    <input type="text" id="txtTargetFrame" class="form-control" onkeyup="OnTargetNameChange();" onchange="OnTargetNameChange();" />
                                </div>
                                <div id="tdPopupName" style="display:none" class="form-group">
                                    <label for="txtPopupName">Имя всплывающего окна</label><br />
                                    <input type="text" id="txtPopupName" class="form-control" />
                                </div>
                                <div class="clear"></div>
                                <div id="tablePopupFeatures" style="display:none;" class="form-group">
                                    <label>Свойства всплывающего окна</label><br />
                                    <table cellspacing="0" cellpadding="0" border="0" width="50%">
                                        <tr>
                                            <td valign="top" nowrap="nowrap" width="50%">
                                                <input id="chkPopupResizable" name="chkFeature" value="resizable" type="checkbox" class="icheck-minimal-green" /><label for="chkPopupResizable">Изменяющееся в размерах</label><br />
                                                <input id="chkPopupLocationBar" name="chkFeature" value="location" type="checkbox" class="icheck-minimal-green" /><label for="chkPopupLocationBar">Панель локации</label><br />
                                                <input id="chkPopupManuBar" name="chkFeature" value="menubar" type="checkbox" class="icheck-minimal-green" /><label for="chkPopupManuBar">Панель меню</label><br />
                                                <input id="chkPopupScrollBars" name="chkFeature" value="scrollbars" type="checkbox" class="icheck-minimal-green" /><label for="chkPopupScrollBars">Полосы прокрутки</label>
                                            </td>
                                            <td></td>
                                            <td valign="top" nowrap="nowrap" width="50%">
                                                <input id="chkPopupStatusBar" name="chkFeature" value="status" type="checkbox" class="icheck-minimal-green" /><label for="chkPopupStatusBar">Строка состояния</label><br />
                                                <input id="chkPopupToolbar" name="chkFeature" value="toolbar" type="checkbox" class="icheck-minimal-green" /><label for="chkPopupToolbar">Панель инструментов</label><br />
                                                <input id="chkPopupFullScreen" name="chkFeature" value="fullscreen" type="checkbox" class="icheck-minimal-green" /><label for="chkPopupFullScreen">Полный экран (IE)</label><br />
                                                <input id="chkPopupDependent" name="chkFeature" value="dependent" type="checkbox" class="icheck-minimal-green" /><label for="chkPopupDependent">Зависимый (Netscape)</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" nowrap="nowrap" width="50%">&nbsp;</td>
                                            <td></td>
                                            <td valign="top" nowrap="nowrap" width="50%"></td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <table cellspacing="0" cellpadding="0" border="0">
                                                    <tr>
                                                        <td nowrap="nowrap"><span>Ширина</span></td>
                                                        <td><input id="txtPopupWidth" type="text" maxlength="4" size="4" class="form-control" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td nowrap="nowrap"><span>Высота</span></td>
                                                        <td><input id="txtPopupHeight" type="text" maxlength="4" size="4" class="form-control" /></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>&nbsp;&nbsp;</td>
                                            <td>
                                                <table cellspacing="0" cellpadding="0" border="0">
                                                    <tr>
                                                        <td nowrap="nowrap"><span>Позиция слева</span></td>
                                                        <td><input id="txtPopupLeft" type="text" maxlength="4" size="4" class="form-control" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td nowrap="nowrap"><span>Позиция сверху</span></td>
                                                        <td><input id="txtPopupTop" type="text" maxlength="4" size="4" class="form-control" /></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <input type="hidden" name="link_url">
                            <input type="hidden" name="target">
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane">
                        <div class="col-md-6">
                            <section class="box">
                                <header class="panel_header">
                                    <h2 class="title pull-left">Картинки (макс. размер 1-го файла: 50 Mb)</h2>
                                </header>
                                <div class="content-body">
                                    <div class="form-group" id="MenuImage1-box">
                                        <label for="MenuImage1">Иконка 1</label><br />
                                    </div>

                                </div>
                            </section>
                        </div>
                    </div>
                        
                    <div class="clearfix"></div>
                </div>


                <div class="top15">
                    @csrf
                    <button type="submit" class="btn btn-success btn-icon right15" onclick="return GenerateLinkHRef(this.form);">
                        <i class="fa fa-save"></i>
                        Сохранить
                    </button>
                    <a class="btn btn-icon" href="{{ route('admin.page.index') }}">
                        <i class="fa fa-ban"></i>
                        Отмена
                    </a>
                </div>
            </form>
        </div>

    </section>
@endsection

@push('footer.scripts')
    <script type="text/javascript" src="{{ asset('assets/admin') }}/js/link.js"></script>

    <script>
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

        var sType = FillLinkDivFromHRef("", "", new Array());
        ShowLinkTypeDiv(sType);
        $('#LinkType').val(sType);
    </script>
@endpush