@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="game-details border-b border-gray-800 pb-12 flex flex-col lg:flex-row">
            <div class="flex-none">
                <img src="{{ $game['coverImageUrl'] }}" alt="cover">
            </div>
            <div class="lg:ml-12 lg:mr-64">
                <h2 class="font-semibold text-4xl leading-tight mt-1">
                    {{ $game['name'] }}
                </h2>
                <div class="text-gray-400">
                    <span>
                        {{ $game['genres'] }}
                    </span>
                    &middot;
                    <span>
                       {{ $game['involved_companies'] }}
                    </span>
                    &middot;
                    <span>
                        {{ $game['platforms'] }}
                    </span>
                </div>
                
                <div class="flex flex-wrap items-center mt-8">
                    <div class="flex items-center">
                        <div id="memberRating" class=" w-16 h-16 bg-gray-800 rounded-full relative text-sm">
                            @push('scripts')
                               @include('_rating', [
                                  'slug' => 'memberRating',
                                   'rating' => $game['memberRating'],
                                   'event' => null,
                               ]) 
                            @endpush
                        </div>
                        <div class="ml-4 text-xs">Member <br> Score</div>
                    </div>
                    
                    <div class="flex items-center ml-12">
                        <div id="criticRating" class=" w-16 h-16 bg-gray-800 rounded-full relative text-sm">
                            @push('scripts')
                                @include('_rating', [
                                'slug' => 'criticRating',
                                    'rating' => $game['criticRating'],
                                    'event' => null,
                                ]) 
                            @endpush
                        </div>
                        <div class="ml-4 text-xs">Critic <br> Score</div>
                    </div> 

                    <div class="flex items-center space-x-4 mt-4 sm:mt-0 ml-12">
                        @if ($game['social']['website'])
                            <div class="bg-gray-800 w-8 h-8 rounded-full flex justify-center items-center">
                            <a href="{{ $game['social']['website']['url'] }}" target="_blank" class="hover:text-gray-400"><i class="fas fa-globe-americas fa-lg"></i></a>
                            </div>
                        @endif
                        
                        @if ($game['social']['instagram'])
                            <div class="bg-gray-800 w-8 h-8 rounded-full flex justify-center items-center">
                                <a href="{{ $game['social']['instagram']['url'] }}" target="_blank" class="hover:text-gray-400"><i class="fab fa-instagram fa-lg"></i></a>
                            </div>
                        @endif

                        @if ($game['social']['twitter'])
                            <div class="bg-gray-800 w-8 h-8 rounded-full flex justify-center items-center">
                                <a href="{{ $game['social']['twitter']['url'] }}" target="_blank" class="hover:text-gray-400"><i class="fab fa-twitter fa-lg"></i></a>
                            </div>
                        @endif

                        @if ($game['social']['facebook'])
                            <div class="bg-gray-800 w-8 h-8 rounded-full flex justify-center items-center">
                                <a href="{{ $game['social']['facebook']['url'] }}" target="_blank" class="hover:text-gray-400"><i class="fab fa-facebook fa-lg"></i></a>
                            </div> 
                        @endif
                    </div>                   
                                     
                </div>

                <p class="mt-12">
                    {{ $game['summary'] }}
                </p>
                
                <div class="mt-12">
                    {{-- <button class="flex items-center bg-blue-500 text-white font-semibold px-4 py-4 hover:bg-blue-600 rounded transition ease-in-out duration-150">
                        <i class="far fa-play-circle"></i>
                        <span class="ml-2">Play Trailer</span> 
                    </button> --}}

                    <a href="{{ $game['trailer'] }}" target="_blank" class="inline-flex items-center bg-blue-500 text-white font-semibold px-4 py-4 hover:bg-blue-600 rounded transition ease-in-out duration-150">
                        <i class="far fa-play-circle"></i>
                        <span class="ml-2">Play Trailer</span> 
                    </a>
                </div>
            </div>
        </div> <!-- end game details -->

        <div class="images-container border-b border-gray-800 pb-12 mt-8">
            <h2 class="text-blue-500 uppercase tracking-wide font-semibold">
                Images
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mt-8">
                @foreach ($game['screenshots'] as $screenshot)
                    <div>
                        <a href="{{ $screenshot['huge'] }}">
                            <img src="{{ $screenshot['big'] }}" alt="screenshot" class="hover:opacity-75 transition ease-in-out duration-150">
                        </a>
                    </div>
                @endforeach                               
            </div>
        </div> <!-- end image container -->

        <div class="similar-games-container mt-8">
            <h2 class="text-blue-500 uppercase tracking-wide font-semibold">
                Similar Games
            </h2>
            <div class="popular-games text-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 xl:grid-cols-6 gap-12">
                @foreach ($game['similar_games'] as $similar_game)
                    <x-game-card :game="$similar_game" />
                @endforeach                
            </div> 
        </div> <!-- end similar-games -->
    </div>
@endsection