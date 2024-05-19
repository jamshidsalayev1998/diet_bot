<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
</head>
<body>
    <div>
        @foreach ($menuParts as $menuPart)
            @php
                $menuTypeTitleJson = json_decode($menuPart['menu_type']['title']);
            @endphp
            <div>
                <h2>{{ $menuTypeTitleJson->$lang }}</h2>
                <ul>
                    @foreach ($menuPart['menu_part_products'] as $product)
                        @php
                            $productTitle = json_decode($product['product']['title']);
                            $productImage = $product['product']['image'];
                            $measureTypeTitle = $product['product']['measure_type']['title'];
                            $permissionDescription = $product['product']['permission_description'] ?? 'Permission Description Not Available';
                        @endphp
                        <li>
                            <img src="{{ $productImage }}" alt="{{ $productTitle->$lang }}" width="50" height="50">
                            <strong>{{ $productTitle->$lang }}</strong> - {{ $measureTypeTitle->$lang }}
                            <p>{{ $permissionDescription->$lang }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</body>
</html>
