<ul>
    @foreach ($items as $menu_item)
        <li>
            <a href="{{ $menu_item->link() }}">

                {{ $menu_item->title }}
                @if ($menu_item->title === 'Cart')
                    <span class="cart-count">
                        @if (Cart::instance('default')->count() > 0)
                            <span>{{ Cart::instance('default')->count() }}</span>
                    </span>
                @endif
    @endif
    </a>
    </li>
    @endforeach
</ul>
