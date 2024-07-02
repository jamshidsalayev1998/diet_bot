<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Full Menu</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    @font-face {
        font-family: "Roboto";
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url("../fonts/Roboto-Regular.woff2") format("woff2");
    }

    @font-face {
        font-family: "Roboto";
        font-style: normal;
        font-weight: 500;
        font-display: swap;
        src: url("../fonts/Roboto-Medium.woff2") format("woff2");
    }

    @font-face {
        font-family: "Roboto";
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url("../fonts/Roboto-Bold.woff2") format("woff2");
    }

    @font-face {
        font-family: "Roboto";
        font-style: normal;
        font-weight: 900;
        font-display: swap;
        src: url("../fonts/Roboto-Black.woff2") format("woff2");
    }

    html {
        font-family: "Roboto", sans-serif;
        color: #333333;
    }

    .menu-types {
        display: flex;
    }

    .menu-types .menu-number {
        margin-right: 10px;
    }

    .menu-types .wrap-left {
        width: 60%;
    }

    .menu-types .wrap-right {
        width: 40%;
        padding-left: 40px;
    }

    .menu-types .menu-item {
        width: 100%;
        margin-bottom: 24px;
        border: 1px solid #ccc;
    }

    .menu-types .add-wrap {
        padding: 12px;
    }

    .menu-types .menu-header {
        display: flex;
        text-align: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 500;
        background-color: #f1f1f1;
        border-bottom: 1px solid #ccc;
        padding: 12px;
        font-size: 20px;
    }

    .menu-types .one-menu-item {
        display: flex;
        padding: 4px 16px 8px 16px;
    }

    .menu-types .one-menu-item:last-child .menu-right {
        border-bottom: none;
    }

    .menu-types .products-wrap {
        padding-bottom: 8px;
    }

    .menu-types .products-wrap_bb {
        border-bottom: 1px solid #eee;
    }

    .menu-types .menu-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0px;
    }

    .menu-types .menu-right {
        width: calc(100% - 22px);
        border-bottom: 1px solid #ccc;
    }

    .menu-types .product-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .menu-types .all-calory {
        color: #2db362;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        background-color: #fff;
    }

    .header-left .logo {
        display: flex;
        align-items: center;
    }

    .header-left .logo img {
        width: 70px;
    }

    .header-left .logo .logo-title {
        font-size: 56px;
        font-weight: 600;
    }

    .header-right .user-name {
        font-size: 22px;
        text-align: right;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .header-right .user-name span {
        font-weight: 600;
        color: #2db362;
    }

    .header-right .daily-calory {
        font-size: 18px;
        text-align: right;
    }

    .red-text {
        color: #d62b22;
    }

    .wrap-right .warning-title {
        display: flex;
        align-items: center;
        color: #ff7a00;
        font-size: 20px;
    }

    .wrap-right .warning-title img {
        margin-right: 10px;
        width: 24px;
    }

    .wrap-right .warning-wrap {
        padding: 16px;
        background-color: #ffeed0;
    }

    .wrap-right .warning-wrap .warning-body {
        padding-left: 32px;
        margin-top: 16px;
    }

    .wrap-right .warning-wrap .warning-body ul li {
        margin-bottom: 10px;
    }

    .premium-box {
        padding: 12px;
    }

    .premium-box .lock-icon {
        display: flex;
        justify-content: center;
    }

    .premium-box .lock-icon svg {
        width: 50px;
        height: 50px;
    }

    .premium-box .lock-icon svg path {
        fill: #2db362;
    }

    .premium-box .premium-text {
        color: #2db362;
        margin-top: 20px;
        text-align: center;
    }

    .lunch-box {
        padding: 12px 16px;
    }

    .lunch-box .lunch-text-1 {
        margin-bottom: 10px;
    }

    .perekus {
        margin-top: 32px;
    }

    .item-bottom {
        padding: 12px;
    }

    .green-text {
        color: #2db362;
    }

    #my-node{
        padding: 30px;
    }

    .w-full {
        width: 100%;
    }

</style>

<body>
    <div id="my-node" class="container">
        <div class="menu-types">
            <div class="w-full">
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
                                @if (!$user_info->is_premium)
                                    <div class="lunch-text-2">
                                        <b class="green-text">Premium obuna</b> sotib oling va biz
                                        sizga sun'iy intelekt yordamida ovqat kaloriyasini hisoblab
                                        beramiz
                                    </div>
                                @endif
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
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js"
    integrity="sha512-7tWCgq9tTYS/QkGVyKrtLpqAoMV9XIUxoou+sPUypsaZx56cYR/qio84fPK9EvJJtKvJEwt7vkn6je5UVzGevw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    let node = document.getElementById("my-node");

    htmlToImage
        .toPng(node)
        .then(function(dataUrl) {
            var img = new Image();
            img.src = dataUrl;
            img.style = "width: 100%;";
            document.body.style = "padding: 30px;";
            document.body.innerHTML = "";
            document.body.appendChild(img);
        })
        .catch(function(error) {
            console.error("oops, something went wrong!", error);
        });
</script>
</body>

</html>
