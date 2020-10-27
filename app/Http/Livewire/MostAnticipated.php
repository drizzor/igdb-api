<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class MostAnticipated extends Component
{
    public $mostAnticipated = [];

    /**
     * Pointer vers le component livewire
     */
    public function render()
    {
        return view('livewire.most-anticipated');
    }

    /**
     * Chargement des données depuis IGDB
     */
    public function loadMostAnticipated()
    {
        $current = Carbon::now()->timestamp;
        $afterFourMonths = Carbon::now()->addMonths(4)->timestamp;
        
        $mostAnticipatedUnformatted = Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
        ])
        ->withToken(env('IGDB_TOKEN'))
        ->withBody("
            fields name, cover.url, first_release_date, hypes, slug; 
            where platforms = (48,49,130,6)
            & (first_release_date > {$current}
            & first_release_date < {$afterFourMonths}
            )
            & cover != null
            & hypes != null;
            sort hypes desc;
            limit 4;"
            ,'text/plain')
        ->post('https://api.igdb.com/v4/games')->json();

        // dump($mostAnticipatedUnformatted);

        $this->mostAnticipated = $this->formatForView($mostAnticipatedUnformatted);
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
