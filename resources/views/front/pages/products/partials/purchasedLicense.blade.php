@php
    /** @var \App\Models\License $license */

    $payLink = auth()->user()->chargeProduct($license->purchasable->paddle_product_id)
@endphp

<div class="cells grid-cols-2">
    <div class="cell-l">
        <code class="font-mono text-blue bg-blue-lightest bg-opacity-25 px-2 py-1 rounded-sm">{{ $license->key }}</code>
        <div class="text-xs text-gray">
            vanbockstal.be
            <span class="char-searator mx-1">•</span>
            Expires at {{ $license->expires_at->format('d/m/Y') }}
            <span class="text-pink-dark">Expires since {{ $license->expires_at->format('d/m/Y') }}</span>
        </div>
    </div>

    <span  class="cell-r flex justify-end space-x-4">
        <x-paddle-button :url="$payLink" data-theme="none">
            <x-button>
                    Renew for 
                    <span class="ml-1 text-lg leading-none">
                        <span class="" data-id="current-currency-{{ $license->purchasable->id }}"></span>
                        <span class="" data-id="current-price-{{ $license->purchasable->id }}"></span>
                    </span>
                </x-button>
        </x-paddle-button>

        <x-button>
            Watch videos
        </x-button>
    </span>
</div>


<script type="text/javascript">
    function indexOfFirstDigitInString(string) {
        let firstDigit = string.match(/\d/);

        return string.indexOf(firstDigit);
    }

    Paddle.Product.Prices({{ $license->purchasable->paddle_product_id }}, function(prices) {
        console.log('license renewal', prices);
        let priceString = prices.price.net;

        let indexOFirstDigitInString = indexOfFirstDigitInString(priceString);

        let price = priceString.substring(indexOFirstDigitInString);
        price = price.replace('.00', '');

        let currencySymbol = priceString.substring(0,indexOFirstDigitInString);
        currencySymbol = currencySymbol.replace('US', '');

        document.querySelector('[data-id="current-currency-{{ $license->purchasable->id}}"]').innerHTML = currencySymbol;
        document.querySelector('[data-id="current-price-{{ $license->purchasable->id }}"]').innerHTML = price;
    });
</script>