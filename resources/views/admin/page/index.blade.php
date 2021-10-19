@if (count($pages) > 0)
    <ul class="uk-nestable" data-uk-nestable>
        @foreach($pages as $page)
            <li data-id="{{ $page->id }}"
                class="page-node uk-nestable-list-item{{ (count($page->children) > 0) ? ' uk-parent uk-collapsed' : '' }}">

                <div class="uk-nestable-item">
                    <div class="uk-nestable-handle"></div>
                    <div data-nestable-action="toggle"></div>

                    <div class="list-label">
                        @if($page->link_id > 0)
                            <a href="{{ route('admin.link.edit', $page->id) }}" class="page-link">
                                {{ $page->title }}
                            </a>
                        @else
                            <a href="{{ route('admin.page.edit', $page->id) }}" class="page-link">
                                {{ $page->title }}
                            </a>
                        @endif
                    </div>

                    @can('delete', $page)
                        <form action="{{ route('admin.page.destroy', $page->id) }}"
                            method="post"
                            class="right-form remove-page-form remove-confirmed">
                            @csrf
                            {{ method_field('delete') }}
                            <button type="button" class="fa fa-close delete" data-title="{{ $page->title }}"></button>
                        </form>
                    @endcan

                    @can('update', $page)
                        <form action="{{ route('admin.page.active', $page->id) }}" method="post" class="right-form">
                            @csrf
                            <input type="hidden" name="active" value="{{ $page->active }}">
                            <button type="button" class="page-switch{{ $page->active ? ' active' : '' }}">
                                <i class="fa fa-power-off" title="Спрятать страницу"></i>
                            </button>
                        </form>
                    @endcan
                </div>

                @if(count($page->children) > 0)
                    <ul class="uk-nestable-list" data-parent="{{ $page->id }}">
                        @include('admin.page._pages', ['pages' => $page->children])
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@else
    @lang('admin/app.empty_list')
@endif

@if($first)
    @push('footer.css')
        <link rel="stylesheet" href="{{ asset('assets/admin') }}/plugins/uikit/css/uikit.min.css">
        <link rel="stylesheet" href="{{ asset('assets/admin') }}/plugins/uikit/css/components/nestable.min.css">
    @endpush

    @push('footer.scripts')
        <script src="{{ asset('assets/admin') }}/plugins/uikit/js/uikit.js"></script>
        <script src="{{ asset('assets/admin') }}/plugins/uikit/js/components/nestable.js"></script>
        <script>
            $('button.page-switch').click(function (e) {
                e.preventDefault();
                var button = $(this);
                var form = button.closest('form');
                form.find('input[name="active"]').val(button.hasClass('active') ? 0 : 1);

                $.ajax({
                    'url': form.attr('action'),
                    'data': form.serialize(),
                    'type': 'post',
                    'dataType': 'json',
                    success: function (data) {
                        if (data && data.success === true) {
                            button.toggleClass('active');
                        }
                    }
                });
            });

            $('.uk-nestable').on('parent.uk.nestable', function (e, li, id, parent, index) {
                
                var data = {
                    'id': id,
                    'parent': parent
                };

                if (index === 0) {
                    data['before'] = li.next('li').data('id');
                } else {
                    data['after'] = li.prev('li').data('id');
                }
                
                saveSortOrder(data);

            });

            $('.uk-nestable').on('sort.uk.nestable', function (e, li, id, index) {

                saveSortOrder({
                    'id': id,
                    'move': index
                });

            });

            function saveSortOrder(data) {
                $.ajax({
                    url: "{{ route('admin.page.sort') }}",
                    type: "put",
                    data: data,
                    dataType: "json",
                    success: function (data) {
                        if (data && data.success && data.success === true) {
                            CreateMessage("Порядок отображения страниц успешно сохранен", 'success');
                        }
                    }
                });
            }

        </script>
    @endpush
@endif
    