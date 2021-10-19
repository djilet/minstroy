@can('create', App\Models\Page::class)
    <div class="pull-left">
        <a href="{{ route('admin.page.create') }}" class="page-add btn btn-primary btn-icon right15 bottom15">
            <i class="fa fa-plus"></i>
            Страница
        </a>
        <a href="{{ route('admin.link.create') }}" class="link-add btn btn-primary btn-icon right15 bottom15">
            <i class="fa fa-plus"></i>
            Ссылка
        </a>
    </div>
    <div class="clearfix"></div>
    <hr>
@endcan

<div id="tree">
    <ul class="nav nav-tabs" id="tree-tabs">
        @foreach($menus as $index => $menu)
            <li class="menu-tab{{ $loop->first ? ' active' : '' }}" data-menuid="{{ $menu->id }}">
                <a class="menu-edit" data-menuid="{{ $menu->id }}" href="#menu-id-{{ $menu->id }}" data-toggle="tab">
                    <span>{{ $menu->title }}</span>
                    @can('delete', $menu)
                        <i class="fa fa-close menu-delete"></i>
                    @endcan
                </a>
            </li>
        @endforeach

        @can('create', App\Models\Menu::class)
            <li><a href="#" id="menu-add">+</a></li>
        @endcan
    </ul>

    <div class="tab-content" id="tree-containers">

        @foreach($menus as $menu)
            <div class="tab-pane menu-container{{ $loop->first ? ' active' : '' }}" id="menu-id-{{ $menu->id }}">

                @include('admin.page.index', ['pages' => $menu->pages->toTree(), 'first' => $loop->first])

            </div>
        @endforeach

    </div>
</div>


<form action="{{ route('admin.menu.destroy', '#id') }}" method="post" class="destroy-menu hidden">
    @csrf
    {{ method_field('delete') }}
</form>


@can('create', App\Models\Menu::class)
    @push('footer.scripts')
        <script>
            saveOpenTab('.content-body', 'openMenuId', '.page-add', '#menu-id-');
            
            $(document).ready(function(){
                $('body')
                    .on('submit', 'form.ajax', function(e){
                        e.preventDefault();
                        var form = $(this);
    
                        $.ajax({
                            'url' : $(this).attr('action'),
                            'data' : $(this).serialize(),
                            'type' : 'post',
                            'dataType' : 'json',
                            success: function(data) {
    
                                if (data.type === 'update') {
    
                                    $('#menu-edit').modal('hide');
                                    $('a.menu-edit[data-menuid="' + data.id + '"] span').text(data.title);
    
                                } else if (data.type === 'create') {
    
                                    $('#menu-create').modal('hide');
                                    var menu = $('<li class="menu-tab" data-menuid="' + data.id + '">' +
                                        '    <a class="menu-edit" data-menuid="' + data.id + '" href="#menu-id-' + data.id + '" data-toggle="tab">' +
                                        '        <span>' + data.title + '</span>' +
                                        '        <i class="fa fa-close menu-delete"></i>' +
                                        '    </a>' +
                                        '</li>');
                                    menu.insertBefore('#tree .nav li:last-of-type');
                                    $('.tab-content').append('<div class="tab-pane menu-container" id="menu-id-' + data.id + '"></div>');
    
                                }
    
                            },
                            error: function(xhr) {
                                form.find('.help-block').remove();
                                form.find('.form-group.has-error').removeClass('has-error');
    
                                var data = xhr.responseJSON;
                                $.each(data.message, function(i, e) {
                                    var group = form.find('input[name="' + i + '"]').closest('.form-group');
                                    group.addClass('has-error');
    
                                    var error = '<span class="help-block">' +
                                        '    <strong>' + e[0] + '</strong>' +
                                        '</span>';
                                    group.append(error);
                                });
                            }
                        });
    
                    })
                    .on('click', '.menu-delete', function(e) {
                        var menu = $(this);
                        var menuId = menu.closest('li').data('menuid');
                        ModalConfirm('Удалить меню?', 'Внимание! Меню будет удалено со всеми его страницами!', function() {
                            var form = $('form.destroy-menu');
                            $.ajax({
                                'url' : form.attr('action').replace('#id', menuId),
                                'data' : form.serialize(),
                                'type' : 'post',
                                'dataType' : 'json',
                                success: function(data) {
                                    if (data.success === true) {
                                        var currentTab = $('li.menu-tab[data-menuid="' + menuId + '"]');
                                        if (currentTab.hasClass('active')) {
                                            var aroundTab = currentTab.prev('.menu-tab').find('.menu-edit');
                                            if (aroundTab.length === 0) {
                                                aroundTab = currentTab.next('.menu-tab').find('.menu-edit');
                                            }
                                            if (aroundTab.length > 0) {
                                                aroundTab.click();
                                            }
                                        }
                                        
                                        currentTab.remove();
                                        $('.tab-content #menu-id-' + menuId).remove();
                                    }
                                }
                            });
                        });
                    })
                    .on('hidden.bs.modal', '.modal', function () {
                        $(this).remove();
                    });

                $('a.menu-edit').dblclick(function(e) {
                    e.preventDefault();
                    var url = "{{ route('admin.menu.edit', '#id') }}".replace('#id', $(this).data('menuid'));
                    $.get(url, function(data) {
                        if (data) {
                            $(data).modal('show');
                        }
                    });
                });

                $('#menu-add').click(function(e) {
                    e.preventDefault();
                    $.get("{{ route('admin.menu.create') }}", function(data) {
                        if (data) {
                            $(data).modal('show');
                        }
                    });
                });
            });
        </script>
    @endpush
@endcan