<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
</head>

<body>
    <div>
        @foreach ($data as $item)
            @php
                $menuTypeTitleJson = json_decode($item['type_title']);
            @endphp
            <h2>{{ $menuTypeTitleJson->$lang }}</h2>
            @foreach ($item['records'] as $menuPart)
                <div style="border: 1px solid black; padding: 4px">
                    <ul>
                        @foreach ($menuPart['menu_part_products'] as $product)
                            @php
                                $productTitle = json_decode($product['product']['title']);
                                $productImage = $product['product']['image'];
                                $measureTypeTitle = json_decode($product['product']['measure_type']['title']);
                                $permissionDescription = json_decode($product['product']['permission_description']);
                            @endphp
                            <li>
                                <img src="{{ $productImage }}" alt="{{ $productTitle->$lang }}" width="50"
                                    height="50">
                                <strong>{{ $productTitle->$lang }}</strong> - {{ $measureTypeTitle->$lang }}
                                {{-- <p>{{ $permissionDescription->$lang }}</p> --}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @endforeach
    </div>
</body>

</html>
