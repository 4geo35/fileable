<x-tt::table :attributes="new \Illuminate\View\ComponentAttributeBag(['drag-gallery-root' . $postfix => true])">
    <x-slot name="head">
        <tr>
            <x-tt::table.heading class="text-left">{{ __("File") }}</x-tt::table.heading>
        </tr>
    </x-slot>
    <x-slot name="body">
        @foreach($gallery as $key => $item)
            <tr drag-gallery-item="{{ $item->id }}"
                drag-galley-item-order="{{ $key }}"
                wire:key="{{ $item->id }}">
                <td>
                    @include("fa::admin.includes.gallery-table-item")
                </td>
            </tr>
        @endforeach
    </x-slot>
</x-tt::table>
