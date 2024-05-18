<div>
    @foreach ($menuParts as $menuPart)
        {{ $menuPart->menu_type->id }}
    @endforeach
</div>
