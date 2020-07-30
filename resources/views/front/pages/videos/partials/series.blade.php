<section id="series" class="section">
    <div class="wrap wrap-6 col-gap-6 row-gap-16 | items-start markup-lists">
        @foreach($allSeries as $series)
            <div class="sm:col-span-3">
                {{--
                <a href="{{ $series->url }}" class="illustration">
                    {{ image("/video-series/{$series->slug}.jpg") }}
                </a>
                --}}

                <div class="line-l">
                    <h2 class="title-sm">
                        <a href="{{ $series->url }}">{{ $series->title }}</a>
                        <div class="title-subtext text-gray">
                            {{ $series->videos()->count() }}
                            {{  \Illuminate\Support\Str::plural('video', $series->videos()->count()) }}

                            @if($series->purchasables->count())
                                <span class="ml-1 tag tag-green">Part of a course</span>
                            @endif
                            @if($series->videos->where('display', \App\Models\Enums\VideoDisplayEnum::SPONSORS)->count())
                                <span class="ml-1  tag tag-pink">Sponsor content</span>
                            @endif
                        </div>
                    </h2>
                    <p class="mt-4">
                        {{ $series->description }}
                    </p>
                    <p class="mt-4">
                        <a class="link-underline link-blue" href="{{ $series->url }}">Watch {{  \Illuminate\Support\Str::plural('video', $series->videos()->count()) }}</a>
                    </p>

                    @includeFirst(["front.pages.videos.partials.series.{$series->slug}", "front.pages.videos.partials.series.default"])
                </div>
            </div>
        @endforeach
    </div>
</section>
