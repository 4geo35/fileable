@push("scripts")
    @if (! $hasSearch)
        <script type="application/javascript">
            document.querySelectorAll("{{ '[drag-gallery-root' . $postfix . ']' }}").forEach(root => {
                root.querySelectorAll("[drag-gallery-grab]").forEach(el => {
                    el.addEventListener("mouseover", e => {
                        e.target.closest('[drag-gallery-item]').setAttribute("draggable", true)
                    })

                    el.addEventListener("mouseleave", e => {
                        e.target.closest('[drag-gallery-item]').removeAttribute("draggable")
                    })
                })

                root.querySelectorAll("[drag-gallery-item]").forEach(el => {
                    el.counter = 0

                    el.addEventListener('dragstart', e => {
                        root.querySelectorAll("[drag-gallery-item]").forEach(innerEl => {
                            innerEl.counter = 0
                        })
                        e.target.setAttribute('dragging', true)
                    })

                    el.addEventListener('drop', e => {
                        e.target.closest('[drag-gallery-item]').querySelectorAll("td").forEach(td => {
                            td.classList.remove('opacity-50')
                        })

                        let draggingEl = root.querySelector('[dragging]')
                        let chosenEl = e.target.closest('[drag-gallery-item]')

                        if (draggingEl.getAttribute('drag-galley-item-order') > chosenEl.getAttribute('drag-galley-item-order'))
                            e.target.closest('[drag-gallery-item]').before(draggingEl)
                        else
                            e.target.closest('[drag-gallery-item]').after(draggingEl)

                        let component = Livewire.find(
                            e.target.closest('[wire\\:id]').getAttribute('wire:id')
                        )

                        let orderIds = Array.from(root.querySelectorAll('[drag-gallery-item]'))
                            .map(itemEl => itemEl.getAttribute('drag-gallery-item'))

                        component.call('reorderGallery', orderIds)
                    })

                    el.addEventListener('dragenter', e => {
                        el.counter++
                        e.target.closest('[drag-gallery-item]').querySelectorAll("td").forEach(td => {
                            td.classList.add('opacity-50')
                        })
                        e.preventDefault()
                    })

                    el.addEventListener('dragover', e => { e.preventDefault() })

                    el.addEventListener('dragleave', e => {
                        el.counter--
                        if (el.counter <= 0)
                            e.target.closest('[drag-gallery-item]').querySelectorAll("td").forEach(td => {
                                td.classList.remove('opacity-50')
                            })
                    })

                    el.addEventListener('dragend', e => {
                        e.target.removeAttribute('dragging')
                    })
                })
            })
        </script>
    @endif
@endpush
