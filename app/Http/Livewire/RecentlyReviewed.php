<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class RecentlyReviewed extends Component
{
    public $recentlyReviewed = [];

    /**
     * Pointer vers le component livewire
     */
    public function render()
    {
        return view('livewire.recently-reviewed');
    }

    /**
     * Chargement des données depuis IGDB
     */
    public function loadRecentlyReviewed()
    {
        $current = Carbon::now()->timestamp;
        $before = Carbon::now()->subMonths(2)->timestamp;
        
        $recentlyReviewedUnformatted = Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
        ])
        ->withToken(env('IGDB_TOKEN'))
        ->withBody("
            fields name, cover.url, first_release_date, platforms.abbreviation, rating, total_rating_count, summary, slug; 
            where platforms = (48,49,130,6)
            & (first_release_date >= {$before}
            & first_release_date < {$current}
            )
            & rating_count > 5
            & cover != null
            & rating != null;
            limit 3;"
            ,'text/plain')
        ->post('https://api.igdb.com/v4/games')->json();

        $this->recentlyReviewed = $this->formatForView($recentlyReviewedUnformatted);
    }

    /**
     * Formattage de certaine information récupérée via mon fetch d'api; Objectif étant de limiter le code dans mes vues
     */
    public function formatForView($games)
    {
        return collect($games)->map(function($game){
            return collect($game)->merge([
                'coverImageUrl' => Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']),
                'rating' => isset($game['rating']) ? round($game['rating']) . '%' : "N/A",
                'platforms' => collect($game['platforms'])->pluck('abbreviation')->implode(', '),
                'summary' => mb_strlen($game['summary']) > 250 ? mb_strimwidth($game['summary'],0, 250) . "(...)" : $game['summary'],
            ]);
        });
    }
}
