<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ComingSoon extends Component
{
    public $comingSoon = [];

    /**
     * Pointer vers le component livewire
     */
    public function render()
    {
        return view('livewire.coming-soon');
    }

    /**
     * Chargement des données depuis IGDB
     */
    public function loadComingSoon()
    {
        $current = Carbon::now()->timestamp;
        
        $comingSoonUnformatted = Http::withHeaders([
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

        $this->comingSoon = $this->formatForView($comingSoonUnformatted);
    }

    /**
     * Formattage de certaine information récupérée via mon fetch d'api; Objectif étant de limiter le code dans mes vues
     */
    private function formatForView($games)
    {
        return collect($games)->map(function ($game){
            return collect($game)->merge([
                'coverImageUrl' => Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']),
                'first_release_date' =>  date('d M, Y', $game['first_release_date']),
            ]);
        });
    }
}
