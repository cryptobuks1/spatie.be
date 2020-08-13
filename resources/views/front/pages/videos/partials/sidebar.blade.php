<nav class="sticky top-0 px-4 py-6 bg-white bg-opacity-50 shadow-light rounded-sm markup-lists">
    <h2 class="title-sm text-sm mb-4">
        {{ $series->title }}
    </h2>
    <ol class="text-xs grid gap-2 links-blue markup-list-compact">
        @forelse ($series->videos as $video)
            <li class="{{ isset($currentVideo) && $currentVideo->id === $video->id ? "font-sans-bold" : "" }}">
                <a class="block" href="{{ route('videos.show', [$series, $video]) }}">
                    <span class="mr-1">{{ $video->title }}</span>
                    
                    @if($video->display === \App\Models\Enums\VideoDisplayEnum::FREE)
                        <span class="hidden tag tag-green">Free</span>
                    @endif

                    @if($video->display === \App\Models\Enums\VideoDisplayEnum::SPONSORS)
                        <span title="Exclusive for sponsors" class="w-4 h-4 inline-flex items-center justify-center bg-pink-lightest rounded-full">
                            <span style="font-size: .6rem" class="icon text-pink">
                                {{ svg('icons/fas-heart') }}
                            </span>
                        </span>
                    @endif

                    @if($video->display === \App\Models\Enums\VideoDisplayEnum::LICENSE &&  ! $video->canBeSeenByCurrentUser() )
                        <span title="Part of course" class="w-4 h-4 inline-flex items-center justify-center bg-green-lightest rounded-full">
                            <span style="font-size: .6rem; top: -.1rem" class="icon text-green">
                                {{ svg('icons/fas-lock-alt') }}
                            </span>
                        </span>
                    @endif

                    @if($video->hasBeenCompletedByCurrentUser())
                        <span title="Completed" style="left: calc(-1.5em - 1.5rem)" class="absolute top-0 w-4 h-4 inline-flex items-center justify-center bg-green rounded-full">
                            <span style="font-size: 0.75rem" class="text-white">
                                ✓
                            </span>
                        </span>
                    @endif
                </a>
            </li>
        @empty
            <li>No videos yet! Stay tuned...</li>
        @endforelse
    </ol>

    @if(!$series->isOwnedByCurrentUser() && $series->isPurchasable())
        <div class="mt-8 py-4 pr-4 line-l line-l-green bg-green-lightest bg-opacity-50">
            These are a samples of a <a href="{{ $series->purchaseLink() }}" class="link-green link-underline">paid course</a>.
            <div class="mt-2">
                <a href="{{ $series->purchaseLink() }}" class="grid">
                    <x-button>
                        Buy entire course
                    </x-button>
                </a>
            </div>

            @if (sponsorIsViewingPage())
                @include('front.pages.videos.partials.sponsorDiscount')
            @endif
        </div>
    @endif
</nav>
