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

                    @if ($game['social'])
                        <div class="flex items-center space-x-4 mt-4 sm:mt-0  ml-12">
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
                    @endif                                                                          
                </div>

                <p class="mt-12">
                    @if (isset($game['summary']))  
                        {{ $game['summary'] }}
                    @endif
                </p>
                
                @if ($game['trailer'])
                    <div x-data="{ isTrailerModalVisible: false }" class="mt-12">
                        <button 
                            class="flex items-center bg-blue-500 text-white font-semibold px-4 py-4 hover:bg-blue-600 rounded transition ease-in-out duration-150"
                            @click="isTrailerModalVisible = true"
                        >
                            <i class="far fa-play-circle"></i>
                            <span class="ml-2">Play Trailer</span>
                        </button>

                        {{-- Modal video trailer --}}
                        <template x-if="isTrailerModalVisible">
                            <div class="fixed z-10 bg-black bg-opacity-50 top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto">
                                <div class="container mx-auto lg:px-32 rounded-lg overflow-y-auto">
                                    <div class="bg-gray-900 rounded" @click.away="isTrailerModalVisible = false">
                                        <div class="flex justify-end pr-4 pt-2">
                                            <button 
                                                class="text-3xl leading-none hover:text-gray-300" 
                                                @click="isTrailerModalVisible = false"
                                                @keydown.escape.window="isTrailerModalVisible = false"
                                            >
                                                &times;
                                            </button>
                                        </div>
                            
                                        <div class="modal-body px-8 py-8">
                                            <div class="responsive-container overflow-hidden relative" style="padding-top: 56.25%">
                                                <iframe width="560" height="315" 
                                                    class="responsive-iframe absolute top-0 left-0 w-full h-full" 
                                                    src="{{ $game['trailer'] }}" 
                                                    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                                                </iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end modal video trailer -->
                        </template>
                    </div> <!-- end button trailer -->
                @endif
                
            </div>
        </div> <!-- end game details -->

        @if ($game['screenshots'])
            <div x-data="{isImageModalVisible: false, image: ''}" class="images-container border-b border-gray-800 pb-12 mt-8">
                <h2 class="text-blue-500 uppercase tracking-wide font-semibold">
                    Images
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mt-8">
                    @foreach ($game['screenshots'] as $screenshot)
                        <div>
                            <a 
                                href="#"
                                @click.prevent="
                                    isImageModalVisible = true
                                    image = '{{ $screenshot['huge'] }}'
                                "
                            >
                                <img src="{{ $screenshot['big'] }}" alt="screenshot" class="hover:opacity-75 transition ease-in-out duration-150">
                            </a>
                        </div>
                    @endforeach                               
                </div>
                
                {{-- Screenshots modal --}}
                <template x-if="isImageModalVisible">
                    <div class="fixed z-10 bg-black bg-opacity-50 top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto">
                        <div class="container mx-auto lg:px-32 rounded-lg overflow-y-auto">
                            <div class="bg-gray-900 rounded" @click.away="isImageModalVisible = false">
                                <div class="flex justify-end pr-4 pt-2">
                                    <button 
                                        class="text-3xl leading-none hover:text-gray-300" 
                                        @click="isImageModalVisible = false"
                                        @keydown.escape.window="isImageModalVisible = false"
                                    >
                                        &times;
                                    </button>
                                </div>
                    
                                <div class="modal-body px-8 py-8">
                                    <img :src="image" alt="screenshot">
                                </div>
                            </div>
                        </div>
                    </div> <!-- end modal screenshots -->
                </template>
            </div> <!-- end image container --> 
        @endif
        

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