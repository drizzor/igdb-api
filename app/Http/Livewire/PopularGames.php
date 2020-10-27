<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PopularGames extends Component
{
    public $popularGames = [];

    /**
     * Pointer vers le component livewire
     */
    public function render()
    {
        return view('livewire.popular-games');
    }

    /**
     * Chargement des données depuis IGDB
     */
    public function loadPopularGames()
    {
        $before = Carbon::now()->subMonths(2)->timestamp;
        $after = Carbon::now()->addMonths(2)->timestamp;
        
        $popularGamesUnformatted = Cache::remember('popular-games', 7, function () use ($before, $after) {
            return Http::withHeaders([
                'Client-ID' => env('IGDB_CLIENT_ID'),
            ])
            ->withToken(env('IGDB_TOKEN'))
            ->withBody("
                fields name, cover.url, first_release_date, platforms.abbreviation, rating, total_rating_count, slug; 
                where platforms = (48,49,130,6)
                & (first_release_date >= {$before} & first_release_date < {$after} & total_rating_count > 15)
                & cover != null;
                sort total_rating_count desc;
                limit 12;"
                ,'text/plain')
            ->post('https://api.igdb.com/v4/games')->json();
        });

        // dump($popularGamesUnformatted);

        $this->popularGames = $this->formatForView($popularGamesUnformatted);
    }

    /**
     * Formattage de certaine information récupérée via mon fetch d'api; Objectif étant de limiter le code dans mes vues
     */
    private function formatForView($games)
    {
        return collect($games)->map(function ($game) {
            return collect($game)->merge([
                'coverImageUrl' => Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']),
                'rating' => isset($game['rating']) ? round($game['rating']) . '%' : "N/A",
                'platforms' => collect($game['platforms'])->pluck('abbreviation')->implode(', '),
            ]);
        });
    }
}
