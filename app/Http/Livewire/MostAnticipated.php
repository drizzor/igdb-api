<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class MostAnticipated extends Component
{
    public $mostAnticipated = [];

    public function render()
    {
        return view('livewire.most-anticipated');
    }

    public function loadMostAnticipated()
    {
        $current = Carbon::now()->timestamp;
        $afterFourMonths = Carbon::now()->addMonths(4)->timestamp;
        
        $this->mostAnticipated = Http::withHeaders([
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
    }
}
