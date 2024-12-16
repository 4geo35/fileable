<div class="flex flex-col sm:flex-row justify-between space-y-indent-half sm:space-y-0">
    <div class="flex flex-col space-y-indent-half md:flex-row md:space-x-indent-half md:space-y-0">
        <input type="text" aria-label="Имя" placeholder="Имя" id="name" class="form-control" wire:model.live="searchName">
        <button type="button" class="btn btn-outline-primary" wire:click="clearSearch">{{ __("Clear") }}</button>
    </div>

    <div>
        <label for="images{{ $postfix }}" class="btn btn-primary cursor-pointer w-full sm:w-auto" wire:loading.attr="disabled">
            {{ __("Choose files") }}
        </label>
        <input type="file" name="images" multiple aria-label="{{ __("Images") }}"
               id="images{{ $postfix }}" class="form-control hidden"
               wire:loading.attr="disabled" wire:model.lazy="images">
    </div>
</div>
