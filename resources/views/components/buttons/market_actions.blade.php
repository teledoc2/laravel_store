<div class="flex items-center space-x-2">

    @role('admin')
    <x-buttons.assign :model="$model" />
    @endrole
    <x-buttons.show :model="$model" />
    <x-buttons.edit :model="$model" />
    @if( $model->is_active )
        <x-buttons.deactivate :model="$model" />
    @else
        <x-buttons.activate :model="$model" />
    @endif



</div>
