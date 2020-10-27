<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');

        // $multiquery = Http::withHeaders([
        //     'Client-ID' => env('IGDB_CLIENT_ID'),
        // ])
        // ->withToken(env('IGDB_TOKEN'))
        // ->withBody("
        //     query games \"popularGames\" {
        //         fields name, cover.url, first_release_date, platforms.abbreviation, rating, total_rating_count; 
        //         where platforms = (48,49,130,6)
        //         & (first_release_date >= {$before} & first_release_date < {$after})
        //         & cover != null
        //         & rating != null;
        //         sort total_rating_count desc;
        //         limit 12;   
        //     };

        //     query games \"recentlyReviewed\" {
        //         fields name, cover.url, first_release_date, platforms.abbreviation, rating, total_rating_count, summary; 
        //         where platforms = (48,49,130,6)
        //         & (first_release_date >= {$before} & first_release_date < {$current})
        //         & rating_count > 5
        //         & cover != null
        //         & rating != null;
        //         limit 3;
        //     };

        //     query games \"mostAnticipated\" {
        //         fields name, cover.url, first_release_date, hypes; 
        //         where platforms = (48,49,130,6)
        //         & (first_release_date > {$current} & first_release_date < {$afterFourMonths})
        //         & cover != null
        //         & hypes != null;
        //         sort hypes desc;
        //         limit 4;
        //     };

        //     query games \"comingSoon\" {
        //         fields name, cover.url, first_release_date, hypes; 
        //         where platforms = (48,49,130,6)
        //         & (first_release_date > {$current})
        //         & hypes > 5
        //         & cover != null;
        //         sort first_release_date asc;
        //         limit 4;
        //     };
        // "
        // ,'text/plain')
        // ->post('https://api.igdb.com/v4/multiquery')->json();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $game = Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
        ])
        ->withToken(env('IGDB_TOKEN'))
        ->withBody("
            fields name, cover.url, first_release_date, platforms.abbreviation, rating,
            slug, involved_companies.company.name, genres.name, aggregated_rating, summary, 
            websites.*, videos.*, screenshots.*, similar_games.cover.url, similar_games.name, 
            similar_games.rating,similar_games.platforms.abbreviation, similar_games.slug;
            where slug = \"{$slug}\";
            limit 1;"
            ,'text/plain')
        ->post('https://api.igdb.com/v4/games')->json();

        // dump($game);

        abort_if(!$game, 404);

        return view('show', [
            'game' => $this->formatGameForView($game[0]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Formattage des informations récupérées depuis l'api IGDB
     */
    private function formatGameForView($game)
    {
        return collect($game)->merge([
            'coverImageUrl' => isset($game['cover'])
                ? Str::replaceFirst('thumb', 'cover_big', $game['cover']['url'])
                : 'https://via.placeholder.com/264x352',
            'genres' => collect($game['genres'])->pluck('name')->implode(', '),
            'involved_companies' => collect($game['involved_companies'])->pluck('company')->pluck('name')->implode(', '),
            'platforms' => isset($game['platforms']) 
                ? collect($game['platforms'])->pluck('abbreviation')->implode(', ') 
                : null,
            'memberRating' => isset($game['rating']) 
                ? round($game['rating']) . '%' 
                : 'N/A',
            'criticRating' => isset($game['aggregated_rating']) 
                ? round($game['aggregated_rating']) . '%' 
                : 'N/A',
            'trailer' => "https://youtube.com/watch/" . $game['videos'][0]['video_id'],
            'screenshots' => collect($game['screenshots'])->map(function ($screenshot) {
                return [
                    'big' => Str::replaceFirst('thumb', 'screenshot_big', $screenshot['url']),
                    'huge' => Str::replaceFirst('thumb', 'screenshot_huge', $screenshot['url']),
                ];
            })->take(9),
            'similar_games' => collect($game['similar_games'])->map(function ($game){
                return collect($game)->merge([
                    'coverImageUrl' => isset($game['cover']) 
                        ? Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) 
                        : 'https://via.placeholder.com/264x352',
                    'rating' => isset($game['rating']) ? round($game['rating']) . '%' : 'N/A',
                    'platforms' => isset($game['platforms']) 
                        ? collect($game['platforms'])->pluck('abbreviation')->implode(', ') 
                        : null,
                ]);
            })->take(6),
            'social' => [
                'website' => collect($game['websites'])->first(),
                'facebook' => collect($game['websites'])->filter(function ($website) {
                    return Str::contains($website['url'], 'facebook');
                })->first(),
                'twitter' => collect($game['websites'])->filter(function ($website) {
                    return Str::contains($website['url'], 'twitter');
                })->first(),
                'instagram' => collect($game['websites'])->filter(function ($website) {
                    return Str::contains($website['url'], 'instagram');
                })->first(),
            ]
        ]);
    }
}
