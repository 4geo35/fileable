<div class="card-body space-y-indent-half">
    @include("fa::admin.includes.image-search")
    @include("fa::admin.includes.images-preview")
    <x-tt::notifications.error prefix="gallery-" />
    <x-tt::notifications.success prefix="gallery-" />
</div>
@include("fa::admin.includes.image-table")
@include("fa::admin.includes.image-table-modals")
