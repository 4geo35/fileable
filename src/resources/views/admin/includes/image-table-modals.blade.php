<x-tt::modal.confirm wire:model="displayDelete">
    <x-slot name="title">{{ __("Delete image") }}</x-slot>
    <x-slot name="text">{{ __("It will be impossible to restore the image!") }}</x-slot>
</x-tt::modal.confirm>
