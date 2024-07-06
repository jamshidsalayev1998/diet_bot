<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Full Menu</title>

    {{-- <link rel="stylesheet" href="{{ asset('menu_styles/styles/style.css') }}" /> --}}
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
        width: 50%;
    }

    .menu-types .wrap-right {
        width: 50%;
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

    #my-node {
        padding: 50px;
    }

    .image-header {
        width: 75px;
        height: 75px;
    }

    .image-header svg {
        width: 100%;
        height: 100%;
    }

    .warning-image {
        width: 24px;
        height: unset;
        display: inline-flex;
        align-items: center;
        margin-right: 8px;
    }

    .warning-image svg {
        width: 24px;
        height: 24px;
    }

    .menu-owner {
  border: 1px solid #eee;
  padding: 16px;
  display: flex;
  margin-top: 24px;
}
.menu-owner .image-left {
  width: 400px;
}
.menu-owner .image-left img {
  width: 100%;
  -o-object-fit: contain;
     object-fit: contain;
}
.menu-owner .info-right {
  padding-left: 20px;
}
.menu-owner .info-right .info-owner-header {
  display: flex;
  align-items: center;
  margin-bottom: 12px;
}
.menu-owner .info-right .fio {
  font-size: 20px;
  font-weight: 600;
  margin-right: 10px;
  line-height: 28px;
}
.menu-owner .info-right .position-info {
  font-size: 18px;
  font-weight: 500;
  color: #ff7a00;
  line-height: 28px;
}
.menu-owner .info-right .about-desc {
  font-size: 16px;
  color: #333333;
}



    /*# sourceMappingURL=style.css.map */
</style>

<body>

    <div id="my-node" class="container">
        <header class="header">
            <div class="header-left">
                <div class="logo">
                    <div class="image-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="264" height="264" viewBox="0 0 264 264"
                            fill="none">
                            <rect width="264" height="264" rx="24" fill="white" />
                            <path
                                d="M121 225.5C149.643 225.5 177.114 215.228 197.368 196.943C217.621 178.658 229 153.859 229 128C229 102.141 217.621 77.3419 197.368 59.0571C177.114 40.7723 149.643 30.5 121 30.5L121 60.152C140.932 60.152 160.048 67.3003 174.142 80.0243C188.237 92.7482 196.155 110.006 196.155 128C196.155 145.994 188.237 163.252 174.142 175.976C160.048 188.7 140.932 195.848 121 195.848L121 225.5Z"
                                fill="#EB4036" />
                            <path
                                d="M69.25 98.6838C69.25 92.0564 74.6226 86.6838 81.25 86.6838H99.25V225.684H69.25V98.6838Z"
                                fill="#F9CE4B" />
                            <path
                                d="M83.25 116.684V86.6838L131.25 86.6838C137.877 86.6838 143.25 92.0564 143.25 98.6838V104.684C143.25 111.311 137.877 116.684 131.25 116.684L83.25 116.684Z"
                                fill="#F9CE4B" />
                            <path
                                d="M69.25 50.9464C69.25 39.7557 78.3219 30.6838 89.5126 30.6838C101.263 30.6838 110.55 40.6462 109.725 52.3676L109.684 52.9615C108.913 63.9281 99.5291 72.2743 88.5475 71.7615C77.7472 71.2571 69.25 62.3539 69.25 51.5418V50.9464Z"
                                fill="#24C279" />
                        </svg>
                    </div>

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
        <div class="wrap-right">
            <div class="warning-wrap">
                <div class="warning-title">
                    <span class="warning-image"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                            height="21" viewBox="0 0 20 21" fill="none">
                            <path
                                d="M9.16667 12.3857L8.95833 7.38567H11.0417L10.8333 12.3857H9.16667ZM11.25 14.8857C11.25 15.2172 11.1183 15.5351 10.8839 15.7695C10.6495 16.004 10.3315 16.1357 10 16.1357C9.66848 16.1357 9.35054 16.004 9.11612 15.7695C8.8817 15.5351 8.75 15.2172 8.75 14.8857C8.75 14.5541 8.8817 14.2362 9.11612 14.0018C9.35054 13.7674 9.66848 13.6357 10 13.6357C10.3315 13.6357 10.6495 13.7674 10.8839 14.0018C11.1183 14.2362 11.25 14.5541 11.25 14.8857Z"
                                fill="#FF7A00" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.94752 2.55066C9.63198 2.33093 10.3681 2.33093 11.0525 2.55066C11.7809 2.784 12.3167 3.3315 12.8025 3.994C13.2842 4.65233 13.8109 5.55983 14.4675 6.69066L17.2425 11.4732C17.89 12.5882 18.4092 13.484 18.7367 14.219C19.0667 14.9607 19.2709 15.684 19.1117 16.4157C18.9594 17.106 18.5913 17.73 18.0609 18.1973C17.495 18.6973 16.7559 18.8815 15.9384 18.9673C15.1284 19.0523 14.08 19.0523 12.7759 19.0523H7.22419C5.92002 19.0523 4.87169 19.0523 4.06169 18.9673C3.24502 18.8815 2.50502 18.6973 1.93919 18.1973C1.40902 17.7299 1.04129 17.1059 0.889188 16.4157C0.729188 15.684 0.933354 14.9598 1.26335 14.219C1.59002 13.484 2.11002 12.5882 2.75752 11.4732L5.53252 6.68983C6.18919 5.56066 6.71669 4.65233 7.19752 3.994C7.68419 3.3315 8.21919 2.78483 8.94752 2.55066ZM10.5267 4.139C10.1842 4.02895 9.81584 4.02895 9.47335 4.139C9.26835 4.20483 8.99835 4.3915 8.57335 4.97233C8.15085 5.549 7.66752 6.37816 6.98002 7.56316L4.25585 12.2582C3.57752 13.4273 3.10335 14.2465 2.81669 14.8898C2.52919 15.5348 2.50252 15.8565 2.54752 16.0632C2.62252 16.4073 2.80585 16.719 3.07252 16.9548C3.23252 17.0965 3.52919 17.2315 4.24169 17.3065C4.95002 17.3807 5.90835 17.3815 7.27502 17.3815H12.725C14.0917 17.3815 15.05 17.3807 15.7584 17.3065C16.4709 17.2315 16.7667 17.0957 16.9275 16.9548C17.1942 16.719 17.3775 16.4073 17.4525 16.0632C17.4975 15.8565 17.4709 15.5348 17.1834 14.889C16.8967 14.2465 16.4225 13.4273 15.7442 12.2582L13.0192 7.56316C12.3325 6.37816 11.8492 5.549 11.4267 4.9715C11.0017 4.3915 10.7317 4.20566 10.5267 4.139Z"
                                fill="#FF7A00" />
                        </svg></span>
                    {{ message_lang('rules_which_need_follow', $lang) }} :
                </div>

                <div class="warning-body">
                    <ul>
                        @foreach ($menu_rules as $menu_rule)
                            @php
                                $titleMenuRule = json_decode($menu_rule->title, true);
                            @endphp
                            <li>{{ $titleMenuRule[$lang] }}</li>
                        @endforeach

                    </ul>
                </div>
            </div>
            <div class="menu-owner">
                <div class="image-left">
                  <img
                    src="https://media.istockphoto.com/id/177373093/photo/indian-male-doctor.jpg?s=612x612&w=0&k=20&c=5FkfKdCYERkAg65cQtdqeO_D0JMv6vrEdPw3mX1Lkfg="
                    alt=""
                  />
                </div>

                <div class="info-right">
                  <div class="info-owner-header">
                    <div class="fio">Ilhom Otajonov</div>
                    <div class="position-info">dietolog, nutritsiolog</div>
                  </div>
                  <div class="about-desc">
                    15 yillik tabribaga ega shifokor. O'zbekiston tibbiyot
                    akademiyasi nutritsiologiya bo'limi dekani. Olimpiya qo'mitasida
                    og'ir atletikachilar bilan dietolog bo'lib ishlagan
                  </div>
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

{{-- <script src="{{ asset('menu_styles/index.js') }}"></script> --}}
<script>
    let node = document.getElementById("my-node");

    htmlToImage
        .toPng(node)
        .then(function(dataUrl) {
            var img = new Image();
            img.src = dataUrl;
            img.style = "width: 100%;";
            document.body.style = "padding: 60px;";
            document.body.innerHTML = "";
            document.body.appendChild(img);
        })
        .catch(function(error) {
            console.error("oops, something went wrong!", error);
        });
</script>
</body>

</html>
