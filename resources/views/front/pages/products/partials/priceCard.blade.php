@auth
    @php($payLink = auth()->user()->chargeProduct($purchasable->paddle_product_id))
@endauth

<div class="bg-white shadow-lg px-8 py-6">
    <h2 class="title-sm mb-6 min-h-10">{{ $purchasable->title }}</h2>
    
    <div class="markup-lists markup-lists-compact text-xs">
        {!! $purchasable->formattedDescription !!}
    </div>

    <div class="mt-6 flex justify-center">
        @auth
            <x-paddle-button :url="$payLink" data-theme="none">
                <x-button>
                    <span>Buy for&nbsp;</span>
                    <span data-id="current-currency-{{ $purchasable->id }}"></span>
                    <span data-id="current-price-{{ $purchasable->id }}"></span>
                </x-button>
            </x-paddle-button>
        @else
            <a href="{{ route('login') }}">
                <x-button>
                    <span>Buy for&nbsp;</span>
                    <span data-id="current-currency-{{ $purchasable->id }}"></span>
                    <span data-id="current-price-{{ $purchasable->id }}"></span>
                </x-button>
            </a>
        @endauth
    </div>
</div>

<script type="text/javascript">
    function indexOfFirstDigitInString(string) {
        let firstDigit = string.match(/\d/);

        return string.indexOf(firstDigit);
    }

    Paddle.Product.Prices({{ $purchasable->paddle_product_id }}, function(prices) {
        let priceString = prices.price.net;
        let indexOFirstDigitInString = indexOfFirstDigitInString(priceString);
        let price = priceString.substring(indexOFirstDigitInString);
        price = price.replace('.00', '');

        let currencySymbol = priceString.substring(0,indexOFirstDigitInString);
        currencySymbol = currencySymbol.replace('US', '');

        document.querySelectorAll('[data-id="current-currency-{{ $purchasable->id}}"]').forEach((element) => {
            element.innerHTML = currencySymbol;
        });
        document.querySelectorAll('[data-id="current-price-{{ $purchasable->id }}"]').forEach((element) => {
            element.innerHTML = price;
        });
    });
</script>
