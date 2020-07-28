<div class="my-8">
    @auth
        @php($payLink = auth()->user()->chargeProduct($purchasable->paddle_product_id))

        <section id="cta" class="section">
            <div class="wrap">
                <div class="card gradient gradient-green text-white">
                    <div class="wrap-card grid md:grid-cols-2 md:items-end">
                        <div class="links-underline links-white">
                            <p class="text-2xl">
                                {{ $purchasable->title }}
                            </p>
                        </div>
                        <h2 class="title-xl md:text-right">
                            <x-paddle-button :url="$payLink" data-theme="none">
                                Buy
                            </x-paddle-button>
                            <span class="text-lg leading-none">
                                <span class="" data-id="current-currency-{{ $purchasable->id }}"></span>
                                <span class="" data-id="current-price-{{ $purchasable->id }}"></span>
                            </span>
                        </h2>
                    </div>
                </div>
            </div>
        </section>
    @else
        <div class="wrap">
            <div class="card gradient gradient-green text-white">
                <div class="wrap-card grid md:grid-cols-2 md:items-end">
                    <div class="links-underline links-white">
                        <p class="text-2xl">
                            Please log in to purchase {{ $purchasable->title }}
                        </p>
                    </div>
                    <h2 class="title-xl md:text-right">
                        <a href="{{ route('login') }}?next={{ url()->current() }}">Log in</a>
                    </h2>
                </div>
            </div>
        </div>
    @endauth
</div>

<script type="text/javascript">
    function indexOfFirstDigitInString(string) {
        let firstDigit = string.match(/\d/);

        return string.indexOf(firstDigit);
    }

    Paddle.Product.Prices({{ $purchasable->paddle_product_id }}, function(prices) {
        console.log(prices);
        let priceString = prices.price.net;

        let indexOFirstDigitInString = indexOfFirstDigitInString(priceString);

        let price = priceString.substring(indexOFirstDigitInString);
        price = price.replace('.00', '');

        let currencySymbol = priceString.substring(0,indexOFirstDigitInString);
        currencySymbol = currencySymbol.replace('US', '');

        document.querySelector('[data-id="current-currency-{{ $purchasable->id}}"]').innerHTML = currencySymbol;
        document.querySelector('[data-id="current-price-{{ $purchasable->id }}"]').innerHTML = price;
    });
</script>
