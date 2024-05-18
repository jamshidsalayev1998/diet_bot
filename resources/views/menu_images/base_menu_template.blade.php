<div>
    @foreach ($menuParts as $menuPart)
    <?php  $title = json_encode($menuPart->menu_type->title['uz']); ?>
        {{ $title }}
    @endforeach
</div>
