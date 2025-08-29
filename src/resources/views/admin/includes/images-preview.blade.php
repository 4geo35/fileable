<div class="row">
    @foreach($forUpload as $key => $item)
        @php
            $imgError = $errors->has("image") && $key === 0;
            $nameError = $errors->has("name") && $key === 0;
        @endphp
        <div class="col w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 2xl:w-1/5 mb-indent">
            @if ($item["preview"])
                <img src="{{ $item['preview'] }}" alt="preview"
                     class="w-full h-60 object-cover rounded-lg border border-transparent mb-indent-half {{ $imgError ? "border-danger" : "" }}">
            @else
                <div class="w-full h-60 mb-indent-half rounded-lg bg-info-lighten flex justify-center items-center">
                    <x-tt::ico.img class="text-white" width="36" height="48" />
                </div>
            @endif
            @if ($imgError) <x-tt::form.error name="image" class="mb-indent-half" /> @endif

            <div class="flex justify-between">
                <input class="form-control {{ $nameError ? "border-danger" : "" }} mr-indent-half"
                       type="text" aria-label="Name"
                       wire:model="forUpload.{{ $key }}.name"
                       @if ($uploadProcess) disabled @endif>

                <button type="button" class="btn btn-danger px-btn-x-ico"
                        wire:click.prevent="deleteImageItem({{ $key }})"
                        @if ($uploadProcess) disabled @endif>
                    <x-tt::ico.trash />
                </button>
            </div>
            @if ($nameError) <x-tt::form.error class="mt-indent-half" name="name" />@endif
        </div>
    @endforeach
    @if (count($forUpload))
        <div class="col w-full">
            <button type="button" class="btn btn-primary btn-lg w-full"
                    wire:click="startUploadImages" wire:loading.attr="disabled"
                    @if ($uploadProcess) disabled @endif>
                Загрузить
            </button>
        </div>
    @endif
</div>
