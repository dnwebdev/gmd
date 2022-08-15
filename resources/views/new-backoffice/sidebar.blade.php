<!-- Main navigation -->
<div class="card card-sidebar-mobile">
    <ul class="nav nav-sidebar" data-nav-type="accordion">
        @php
        $sidebar_menu = require(resource_path('views/new-backoffice/sidebar_menu.php'));
        @endphp

        @foreach ($sidebar_menu as $menu)
        <li class="nav-item{{ !empty($menu['child']) ? ' nav-item-submenu' : '' }}">
            @php
            $active = false;
            $active_child = false;
            if (isset($menu['url'])) {
                $path = parse_url($menu['url'], PHP_URL_PATH) ?? '/';
                $active = request()->getPathInfo() == $path;
            }

            if (isset($menu['child'])) {
                $child_link = collect($menu['child'])->map(function ($item) {
                    return parse_url($item['url'], PHP_URL_PATH); 
                })->toArray();

                //$active_child = request()->is(...$child_link);
                $active_child = in_array(request()->getPathInfo(), $child_link);
            }

            @endphp
            <a href="{{ $menu['url'] ?? '#' }}" class="nav-link{{ $active ? ' active' : '' }}">
                {!! !empty($menu['icon']) ? '<i class="'.$menu['icon'].'"></i>' : '' !!}
                <span>
                    {{ $menu['name'] }}
                </span>
            </a>
            @if (!empty($menu['child']))
            <ul class="nav nav-group-sub" data-submenu-title="{{ $menu['name'] }}" style="display: {{ $active_child ? 'block' : 'none' }};">
                @foreach ($menu['child'] as $child)
                <li class="nav-item">
                    @php
                        $path = parse_url($child['url'], PHP_URL_PATH) ?? '/';
                        $active = request()->getPathInfo() == $path;
                    @endphp
                    <a href="{{ $child['url'] }}" class="nav-link{{ $active ? ' active' : '' }}">
                        {!! !empty($child['icon']) ? '<i class="'.$child['icon'].'"></i>' : '' !!}
                        {{ $child['name'] }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @endforeach
    </ul>
</div>
<!-- /main navigation -->
