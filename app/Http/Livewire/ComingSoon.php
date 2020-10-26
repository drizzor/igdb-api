<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class ComingSoon extends Component
{
    public $comingSoon = [];

    public function render()
    {
        return view('livewire.coming-soon');
    }

    public function loadComingSoon()
    {
        $current = Carbon::now()->timestamp;
        
        $this->comingSoon = Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
        ])
        ->withToken(env('IGDB_TOKEN'))
        ->withBody("
            fields name, cover.url, first_release_date, hypes, slug; 
            where platforms = (48,49,130,6)
            & (first_release_date > {$current})
            & hypes > 5
            & cover != null;
            sort first_release_date asc;
            limit 4;"
            ,'text/plain')
        ->post('https://api.igdb.com/v4/games')->json();
    }
}
