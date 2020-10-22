<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
