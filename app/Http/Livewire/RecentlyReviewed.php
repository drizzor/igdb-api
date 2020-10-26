<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class RecentlyReviewed extends Component
{
    public $recentlyReviewed = [];

    public function render()
    {
        return view('livewire.recently-reviewed');
    }

    public function loadRecentlyReviewed()
    {
        $current = Carbon::now()->timestamp;
        $before = Carbon::now()->subMonths(2)->timestamp;
        
        $this->recentlyReviewed = Http::withHeaders([
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
    }
}
