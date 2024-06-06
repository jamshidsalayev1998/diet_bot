<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Full Menu</title>

    <link rel="stylesheet" href="{{ asset('menu_styles/styles/style.css') }}" />
</head>

<body>

    <div id="my-node" class="container">
        <header class="header">
            <div class="header-left">
                <div class="logo">
                    <img src="{{ asset('menu_styles/images/header-logo.png') }}" alt="" />
                    <div class="logo-title">Dieto</div>
                </div>
            </div>
            <div class="header-right">
                <div class="user-name">
                    <span>{{ $user_info->fio }},</span> {{ message_lang('special_menu_for_you', $lang) }}
                </div>
                <div class="daily-calory">
                    {{ message_lang('daily_need_calories', $lang) }}:
                    <b class="red-text">{{ $user_info->daily_need_calories }} kcal</b>
                </div>
            </div>
        </header>

        <div class="menu-types">
            <div class="wrap-left">
                @foreach ($data as $key => $item)
                    @php
                        $menuTypeTitleJson = json_decode($item['type_title']);
                    @endphp
                    <div class="menu-item">
                        <div class="menu-header">{{ $menuTypeTitleJson->$lang }}</div>
                        <div class="item-bottom">
                            @if ($key != 2)
                                <div class="menus-body">
                                    @php
                                        $menuPartIndex = 0;
                                    @endphp
                                    @foreach ($item['records'] as $menuPart)
                                        <div class="one-menu-item">
                                            <b class="menu-number">{{ ++$menuPartIndex }}.</b>
                                            <div class="menu-right">
                                                <div class="products-wrap products-wrap_bb">

                                                    @foreach ($menuPart['menu_part_products'] as $product)
                                                        @php
                                                            $productTitle = json_decode($product['product']['title']);
                                                            $productImage = $product['product']['image'];
                                                            $measureTypeTitle = json_decode(
                                                                $product['product']['measure_type']['title'],
                                                            );
                                                            $measureCupTitle = $product['product']['measure_cup']
                                                                ? json_decode(
                                                                    $product['product']['measure_cup']['title'],
                                                                )
                                                                : null;
                                                            $permissionDescription = json_decode(
                                                                $product['product']['permission_description'],
                                                            );
                                                        @endphp
                                                        <div class="product-item">
                                                            <div>{{ $productTitle->$lang }}
                                                                {{ $product['measure_type_count'] }}
                                                                {{ $measureTypeTitle ? $measureTypeTitle->$lang : '' }}
                                                                @if ($product['measure_cup_count'])
                                                                    ({{ $product['measure_cup_count'] }}
                                                                    {{ $measureCupTitle ? $measureCupTitle->$lang : '' }})
                                                                @endif
                                                            </div>
                                                            <b>{{ $product['calories'] }} kkal</b>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if (count($menuPart['menu_part_products']) > 1)
                                                    <div class="menu-bottom">
                                                        <div></div>
                                                        <b class="all-calory">{{ $menuPart['calories'] }} kkal</b>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($menuPartIndex == 2 && !$user_info->is_premium)
                                        @break
                                    @endif
                                @endforeach

                            </div>
                        @else
                            <div class="lunch-box">
                                <div class="lunch-text-1">
                                    Siz tushlikda <b>600 kcal</b> iste'mol qilishingiz kerak
                                </div>
                                <div class="lunch-text-2">
                                    <b class="green-text">Premium obuna</b> sotib oling va biz
                                    sizga sun'iy intelekt yordamida ovqat kaloriyasini hisoblab
                                    beramiz
                                </div>
                            </div>
                        @endif
                        @if (!$user_info->is_premium)
                            <div class="premium-box">
                                <div class="lock-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="800px"
                                        width="800px" version="1.1" id="Layer_1" viewBox="0 0 330 330"
                                        xml:space="preserve">
                                        <g id="XMLID_509_">
                                            <path id="XMLID_510_"
                                                d="M65,330h200c8.284,0,15-6.716,15-15V145c0-8.284-6.716-15-15-15h-15V85c0-46.869-38.131-85-85-85   S80,38.131,80,85v45H65c-8.284,0-15,6.716-15,15v170C50,323.284,56.716,330,65,330z M180,234.986V255c0,8.284-6.716,15-15,15   s-15-6.716-15-15v-20.014c-6.068-4.565-10-11.824-10-19.986c0-13.785,11.215-25,25-25s25,11.215,25,25   C190,223.162,186.068,230.421,180,234.986z M110,85c0-30.327,24.673-55,55-55s55,24.673,55,55v45H110V85z" />
                                        </g>
                                    </svg>
                                </div>

                                <div class="premium-text">
                                    {{ message_lang('for_more_menu_parts_buy_premium', $lang) }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="wrap-right">
            <div class="warning-wrap">
                <div class="warning-title">
                    <img src="./images/warning.svg"
                        alt="" />{{ message_lang('rules_which_need_follow', $lang) }} :
                </div>

                <div class="warning-body">
                    <ul>
                        @foreach ($menu_rules as $menu_rule)
                            @php
                                $titleMenuRule = json_decode($menu_rule->title , true);
                            @endphp
                            <li>{{ $titleMenuRule[$lang] }}</li>
                        @endforeach

                    </ul>
                </div>
            </div>

            {{-- <div class="menu-item perekus">
                <div class="menu-header">Perekuslar</div>

                <div class="item-bottom">
                    <div class="menus-body">
                        <div class="one-menu-item">
                            <b class="menu-number">1.</b>
                            <div class="menu-right">
                                <div class="products-wrap">
                                    <div class="product-item">
                                        <div>Grechka 75 gramm (5 qoshiq)</div>
                                        <b>100 kkal</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="one-menu-item">
                            <b class="menu-number">1.</b>
                            <div class="menu-right">
                                <div class="products-wrap">
                                    <div class="product-item">
                                        <div>Grechka 75 gramm (5 qoshiq)</div>
                                        <b>100 kkal</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="one-menu-item">
                            <b class="menu-number">1.</b>
                            <div class="menu-right">
                                <div class="products-wrap">
                                    <div class="product-item">
                                        <div>Grechka 75 gramm (5 qoshiq)</div>
                                        <b>100 kkal</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js"
    integrity="sha512-7tWCgq9tTYS/QkGVyKrtLpqAoMV9XIUxoou+sPUypsaZx56cYR/qio84fPK9EvJJtKvJEwt7vkn6je5UVzGevw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('menu_styles/index.js') }}"></script>
</body>

</html>
