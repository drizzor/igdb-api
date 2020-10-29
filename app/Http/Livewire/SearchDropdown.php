<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SearchDropdown extends Component
{
    public $search = '';
    public $searchResults = [];

    public function render()
    {
        if(strlen($this->search) >= 2) {
            $this->searchResults = Http::withHeaders([
                'Client-ID' => env('IGDB_CLIENT_ID'),
            ])
            ->withToken(env('IGDB_TOKEN'))
            ->withBody("
                search \"{$this->search}\";
                fields name, cover.url, slug; 
                limit 8;"
                ,'text/plain')
            ->post('https://api.igdb.com/v4/games')->json();
        }        

        // dump($this->searchResults);
        
        return view('livewire.search-dropdown');
    }
}