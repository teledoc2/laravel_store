<div class="flex items-center space-x-2">

    <x-buttons.show :model="$model" />
    <x-buttons.plain wireClick="$emit('initiateAssign', {{ $model->id }} )" title="">
        <x-heroicon-o-book-open class="w-5 h-5 mr-2"/>
        <span class="">Menu</span>
    </x-buttons.plain>
    <x-buttons.edit :model="$model" />
    @if( $model->is_active )
        <x-buttons.deactivate :model="$model" />
    @else
        <x-buttons.activate :model="$model" />
    @endif

</div>
