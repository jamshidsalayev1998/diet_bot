<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .menu-types {
            display: flex;
            border: 1px solid #ccc;
        }

        .menu-types .wrap-left {
            width: 60%;
        }

        .menu-types .wrap-right {
            width: 40%;
        }

        .menu-types .menu-item {
            width: 100%;
            border-right: 1px solid #ccc;
        }

        .menu-types .add-wrap {
            padding: 12px;
        }

        .menu-types .menu-header {
            display: flex;
            text-align: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            border-bottom: 1px solid #ccc;
            padding: 12px;
        }

        .menu-types .one-menu-item {
            display: flex;
            padding: 12px;
            border-bottom: 1px solid #ccc;
        }

        .menu-types .one-menu-item:last-child {
            border-bottom: none;
        }

        .menu-types .products-wrap {
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .menu-types .menu-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0px;
        }

        .menu-types .menu-right {
            width: calc(100% - 25px);
        }

        .menu-types .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-types .all-calory {
            color: #2db362;
        }

        /*# sourceMappingURL=style.css.map */
    </style>
</head>

<body>
    <div id="my-node" class="container">
        <div class="menu-types">
            <div class="wrap-left">
                @foreach ($data as $item)
                    @php
                        $menuTypeTitleJson = json_decode($item['type_title']);
                    @endphp
                    <div class="menu-item">
                        <div class="menu-header">{{ $menuTypeTitleJson->$lang }}</div>
                        <div class="menus-body">
                            @foreach ($item['records'] as $menuPart)
                                <div class="one-menu-item">
                                    <b class="menu-number">1.</b>
                                    <div class="menu-right">
                                        @foreach ($menuPart['menu_part_products'] as $product)
                                            @php
                                                $productTitle = json_decode($product['product']['title']);
                                                $productImage = $product['product']['image'];
                                                $measureTypeTitle = json_decode(
                                                    $product['product']['measure_type']['title'],
                                                );
                                                $measureCupTitle = $product['product']['measure_cup']
                                                    ? json_decode($product['product']['measure_cup']['title'])
                                                    : null;
                                                $permissionDescription = json_decode(
                                                    $product['product']['permission_description'],
                                                );
                                            @endphp
                                            <div class="products-wrap">
                                                <div class="product-item">
                                                    <div>{{ $productTitle->$lang }} {{ $product['measure_type_count'] }}
                                                        {{ $measureTypeTitle ? $measureTypeTitle->$lang : '' }}
                                                        @if ($product['measure_cup_count'])
                                                            ({{ $product['measure_cup_count'] }}
                                                            {{ $measureCupTitle ? $measureCupTitle->$lang : '' }})
                                                        @endif
                                                    </div>
                                                    <b>100 kkal</b>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="menu-bottom">
                                            <div></div>
                                            <b class="all-calory">200 kkal</b>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </div>
            <div class="wrap-right"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js"
        integrity="sha512-7tWCgq9tTYS/QkGVyKrtLpqAoMV9XIUxoou+sPUypsaZx56cYR/qio84fPK9EvJJtKvJEwt7vkn6je5UVzGevw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>
