<div>
    @if ($noCardCover)
        @include("fa::admin.includes.card-content")
    @else
        <div class="row">
            <div class="col w-full">
                <div class="card">
                    <div class="card-header">
                        <h2 class="font-medium text-2xl">{{ __("Gallery") }}</h2>
                    </div>
                    @include("fa::admin.includes.card-content")
                </div>
            </div>
        </div>
    @endif
</div>

@include("fa::admin.includes.gallery-draggable-script")
