<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//тесты
Route::get('/test_api', 'TestController@test_api'); //проверка работы api laravel

//HomePage
Route::get('/get_spotify_tracks_count', 'SpotifyAPIController@getSpotifyUserTracksCount'); //получить кол-во треков в библиотеке
Route::get('/get_spotify_username', 'SpotifyAPIController@getSpotifyUsername'); //получить имя пользователя
Route::get('/get_site_info', 'HomeController@getSiteInfo'); //получить информацию о сайте

//Profile
Route::get('/get_spotify_profile', 'SpotifyAPIController@getSpotifyProfile'); //получить профиль пользователя
Route::get('/get_spotify_track_count', 'SpotifyAPIController@getSpotifyTrackCount'); //получить количество треков в библиотеке
Route::get('/get_spotify_last_five/{entity}', 'SpotifyAPIController@getSpotifyLastFive'); //получить последние 5 треков
Route::get('/get_spotify_album_count', 'SpotifyAPIController@getSpotifyAlbumCount'); //получить количество альбомов в библиотеке
Route::get('/get_spotify_artists', 'SpotifyAPIController@getSpotifyArtists'); //получить подписки на исполнителей
Route::get('/get_spotify_five_artists', 'SpotifyAPIController@getSpotifyFiveArtists'); //получить 5 случайних исполнителей из подписок
?>