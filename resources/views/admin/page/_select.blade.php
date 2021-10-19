@foreach($pages as $page)
    
    @if(!empty($currentPage) and $currentPage->id == $page->id)
        @continue
    @endif
    
    <option value="{{ $page->id }}" 
            data-menu="{{ $menu->id }}"
            data-path="{{ $page->pathSlug() }}/"
            {{ (!empty($currentPage) and $currentPage->parent_id == $page->id) ? ' selected' : '' }}
            {{ (!empty($parent_id) and $parent_id == $page->id) ? ' selected' : '' }}>
        
        {!! str_repeat("&nbsp;&nbsp;&nbsp;", $depth ?? 0) !!}
        {{ $page->title }}
    </option>
    
    @if(count($page->children) > 0)
        @include('admin.page._select', [
            'pages' => $page->children, 
            'depth' => isset($depth) ? ++$depth : 1,
            'currentPage' => isset($currentPage) ? $currentPage : null,
            'parent_id' => isset($parent_id) ? $parent_id : null
        ])
    @endif
    
@endforeach