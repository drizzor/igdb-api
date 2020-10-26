<div wire:init="loadRecentlyReviewed" class="recently-reviewed-container space-y-12 mt-8">
    @forelse ($recentlyReviewed as $game)
        <div class="game bg-gray-800 rounded-lg shadow-md flex px-6 py-6">                        
            <div class="relative flex-none">
                <a href="{{ route('games.show', $game['slug']) }}">
                    <img src="{{ Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) }}" alt="game cover" class="w-48 hover:opacity-75 transition ease-in-out duration-150">
                </a>
                <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-900 rounded-full" style="right:-20px; bottom:-20px">
                    <div class="font-semibold text-xs flex justify-center items-center h-full">
                        {{ round($game['rating']) }}%
                    </div>
                </div>
            </div> 
            <div class="ml-12">
                <a href="{{ route('games.show', $game['slug']) }}" class="block text-lg font-semibold leading-tight hover:text-gray-400 mt-4">
                    {{ $game['name'] }}
                </a>
                <div class="text-gray-400 mt-1">
                    @foreach ($game['platforms'] as $platform)
                        {{ $platform['abbreviation'] }},
                    @endforeach
                </div>
                <div class="mt-6 text-gray-400 hidden lg:block">
                    {{ mb_strlen($game['summary']) > 250 ? mb_strimwidth($game['summary'],0, 250) . "(...)" : $game['summary'] }}
                </div>
            </div> 
        </div> 
    @empty 
        @foreach (range(1, 3) as $game)
            <div class="game bg-gray-800 rounded-lg shadow-md flex px-6 py-6">                        
                <div class="relative flex-none">
                    <div class="bg-gray-700 w-44 h-56"></div>
                </div> 
                <div class="ml-12">
                    <div class="inline-block text-transparent bg-gray-700 rounded text-lg leading-tight">
                        Title goes here
                    </div>
        
                    <div class="mt-8 space-y-4 hidden lg:block">
                        <span class="text-transparent bg-gray-700 rounded inline-block">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Lorem, ipsum.</span>
                        <span class="text-transparent bg-gray-700 rounded inline-block">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Lorem, ipsum.</span>
                        <span class="text-transparent bg-gray-700 rounded inline-block">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Lorem, ipsum.</span>
                    </div>
                </div> 
            </div>
        @endforeach    
    @endforelse
</div>