@if (count($pages) > 0)
    @foreach($pages as $page)
        <li data-id="{{ $page->id }}" class="page-node uk-nestable-list-item{{ (count($page->children) > 0) ? ' uk-parent uk-collapsed' : '' }}">

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
                    <form action="{{ route('admin.page.destroy', $page->id) }}" method="post" class="right-form remove-page-form remove-confirmed">
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
@else
    @lang('admin/app.empty_list')
@endif