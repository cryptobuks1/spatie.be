@push('head')
    @paddleJS
@endpush

<x-page
        :title="$product->title"
        background="/backgrounds/home.jpg"
>
    @includeFirst(["front.pages.products.detail.{$product->slug}", "front.pages.products.detail.default"])
</x-page>