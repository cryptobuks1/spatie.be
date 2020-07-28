<footer class="bg-grey-lightest gradient shadow-inner-light | print:shadow-none print:bg-transparent print:gradient-none" style="--gradient-angle: 120deg; --gradient-from:#f3efea; --gradient-to:#e1ded9;">
    <div class="flex-none pt-16 pb-8 | print:pb-2" role="navigation">
        <div class="wrap links links-grey leading-loose | md:leading-normal">
            @include('layout.partials.menu')
            <hr class="my-8 h-2px text-grey opacity-25 rounded | print:text-black" style="page-break-after: avoid;">
            <div class="grid gap-4 text-sm | sm:grid-cols-2 sm:gap-8 | md:flex flex-row-reverse justify-between">
                <address class="grid gap-4 | sm:gap-0 | md:grid-flow-col md:gap-8 md:text-right">
                    <div>
                        <a class="group flex items-end | md:flex-row-reverse" href="https://goo.gl/maps/A2zoLK3nVF9V8jydA" target="_blank" rel="nofollow noreferrer noopener">
                            <span>
                                Kruikstraat 22, Box 12
                                <br>
                                2018 Antwerp, Belgium
                            </span>
                            <span class="icon px-1 fill-current text-grey-lighter group-hover:text-pink transition-all transition-100 | print:hidden">
                                {{ svg('icons/fas-map-marker-alt') }}
                            </span>
                        </a>
                    </div>
                    <div>
                        <a href="mailto:info@spatie.be">info@spatie.be</a>
                        <br>
                        <a href="#tel">+32 3 292 56 79</a>
                    </div>
                </address>
                <div class="hidden | md:grid md:grid-flow-col md:gap-6 | print:hidden">
                    @include('layout.partials.service')
                </div>
                
            </div>
        </div>
    </div>

    <div class="wrap">
        <ul class="grid md:grid-flow-col justify-center md:gap-6 links links-grey text-xs py-4 opacity-50 | print:hidden">
            <li>
                <a href="https://github.com/spatie" target="_blank" rel="nofollow noreferrer noopener">
                    GitHub
                </a>
            </li>
            <li>
                <a href="https://www.instagram.com/spatie_be" target="_blank" rel="nofollow noreferrer noopener">
                    Instagram
                </a>
            </li>
            <li>
                <a href="https://twitter.com/spatie_be" target="_blank" rel="nofollow noreferrer noopener">
                    Twitter
                </a>
            </li>
            <li class="opacity-50">•</li>
            <li><a href="{{ route('legal.privacy') }}">Privacy</a></li>
            <li><a href="{{ route('legal.disclaimer') }}">Disclaimer</a></li>
        </ul>
    </div>
</footer>

@include('layout.partials.modal-telephone')
