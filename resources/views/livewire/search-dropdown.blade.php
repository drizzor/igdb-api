<div class="relative" x-data="{ isVisible: true }" @click.away="isVisible = false">
    <input 
        wire:model.debounce.300ms="search"
        @focus="isVisible = true"
        @keydown.escape.window = "isVisible = false"
        @keydown="isVisible = true"
        @keydown.shift.tab="isVisible = false"
        @keydown.window="
            if(event.keyCode === 106) {
                event.preventDefault();
                $refs.search.focus();
            }
        "
        x-ref="search"
        type="text" 
        class="bg-gray-800 text-sm rounded-full px-3 py-1 w-64 focus:outline-none focus:shadow-outline pl-8" 
        placeholder="Search (Press '*' to focus)"> 

    {{-- {{ dump($search) }} --}}
    
    <div class="absolute top-0 flex items-center h-full ml-2">
        <svg class="fill-current text-gray-400 w-4" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" class="svg-inline--fa fa-search fa-w-16" 
        role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" 
        d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg>
    </div>

    <div wire:loading class="spinner top-0 right-0 mr-4 mt-3" style="position: absolute"></div>

    {{-- Result Dropdown--}}
    @if (strlen($search) >= 2)
        <div class="absolute z-50 bg-gray-800 text-sm rounded w-64 mt-1 shadow-xl" x-show.transition.opacity.duration.1000="isVisible">
            @if (count($searchResults) > 0)
                <ul>
                    @foreach ($searchResults as $game)
                        @if(isset($game['slug']) && isset($game['name']))
                            <li class="border-b border-gray-700">
                                <a href="{{ route('games.show', $game['slug']) }}" 
                                    class="hover:bg-gray-700 px-3 py-3 flex items-center transition ease-in-out duration-150"
                                    @if ($loop->last) @keydown.tab="isVisible=false" @endif> 
                                    
                                    @if (isset($game['cover']))
                                        <img src="{{ Str::replaceFirst('thumb', 'cover_small', $game['cover']['url']) }}" alt="cover" class="w-10">
                                    @else 
                                        <img src="https://via.placeholder.com/264x352" alt="cover" class="w-10">
                                    @endif                        
                                    <span class="ml-4">{{ $game['name'] }}</span>
                                </a>
                            </li> 
                        @else 
                            <div class="px-3 py-3 mt-1 rounded shadow-xl">Nooo results for "{{ $search }}"</div>
                        @endif                         
                    @endforeach     
                </ul>
            @else 
                <div class="px-3 py-3 mt-1 rounded shadow-xl">No results for "{{ $search }}"</div>
            @endif
        </div>
    @endif
    
</div>