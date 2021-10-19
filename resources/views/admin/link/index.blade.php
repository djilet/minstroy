@if (count($pages) > 0)
    <ul class="uk-nestable">
        @foreach($pages as $page)
            <li data-id="{{ $page->id }}" class="page-node uk-nestable-list-item">

                <div class="uk-nestable-item">
                    <div class="uk-nestable-handle"></div>
                    <div data-nestable-action="toggle"></div>
                    <div class="list-label">
                        <a href="{{ route('admin.page.edit', $page->id) }}" class="page-link">
                            {{ $page->title }}
                        </a>
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
                    @include('admin.page.index', ['pages' => $page->children, 'inner_level' => 1])
                @endif
            </li>
        @endforeach
    </ul>
@else
    @lang('admin/app.empty_list')
@endif

@if(!isset($inner_level))
    @push('footer.css')
        <link rel="stylesheet" href="{{ asset('assets/admin') }}/plugins/uikit/css/uikit.min.css">
        <link rel="stylesheet" href="{{ asset('assets/admin') }}/plugins/uikit/css/components/nestable.min.css">
    @endpush
    
    @push('footer.scripts')
        <script src="{{ asset('assets/admin') }}/plugins/uikit/js/uikit.js"></script>
        <script src="{{ asset('assets/admin') }}/plugins/uikit/js/components/nestable.js"></script>
    @endpush
@endif