<div>
    @foreach ($menuParts as $menuPart)
        {{ $menuPart->menu_type->title[$lang] }}
    @endforeach
</div>
