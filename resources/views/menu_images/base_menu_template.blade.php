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
                $menuTypeTitle = $menuPart['menu_type']['title']->$lang ?? 'Title Not Available';
            @endphp
            <div>
                <h2>{{ $menuTypeTitle }}</h2>
                <ul>
                    @foreach ($menuPart->menu_part_products as $product)
                        @php
                            $productTitle = $product->product->title[$lang] ?? 'Product Title Not Available';
                            $productImage = $product->product->image;
                            $measureTypeTitle = $product->product->measure_type->title[$lang] ?? 'Measure Type Not Available';
                            $permissionDescription = $product->product->permission_description[$lang] ?? 'Permission Description Not Available';
                        @endphp
                        <li>
                            <img src="{{ $productImage }}" alt="{{ $productTitle }}" width="50" height="50">
                            <strong>{{ $productTitle }}</strong> - {{ $measureTypeTitle }}
                            <p>{{ $permissionDescription }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</body>
</html>
