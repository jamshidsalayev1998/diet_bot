<div>
    @foreach ($menuParts as $menuPart)
        {{ $menuPart->menu_type->title['uz'] }}
    @endforeach
</div>
