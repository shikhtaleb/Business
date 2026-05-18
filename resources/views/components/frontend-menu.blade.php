@props(['menu', 'lang' => 'ar', 'class' => ''])

@if($menu && $menu->rootItems->isNotEmpty())
    @foreach($menu->rootItems as $item)
        @php
            $label = $item->getLabel($lang);
            $hasChildren = $item->children->isNotEmpty();
        @endphp

        @if($hasChildren)
            <div class="nav-dropdown">
                <button class="nav-dropdown-btn" aria-expanded="false" aria-haspopup="true">
                    {{ $label }}
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="nav-dropdown-menu">
                    @foreach($item->children as $child)
                        <a href="{{ $child->url }}"
                           @if($child->target === '_blank') target="_blank" rel="noopener" @endif>
                            {{ $child->getLabel($lang) }}
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <a href="{{ $item->url }}"
               @if($item->target === '_blank') target="_blank" rel="noopener" @endif>
                {{ $label }}
            </a>
        @endif
    @endforeach
@endif
