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

//Spotify Token Check
Route::get('/check_token', 'SpotifyAuthController@checkSpotifyToken');

//Navigation
Route::get('/get_nav_settings', 'HomeController@getNavigationSettings'); //настройки навигации

//HomePage
Route::get('/get_welcome_message', 'HomeController@getWelcomeMessage'); //получить welcome msg
Route::get('/check_cookies', 'SpotifyAuthController@checkCookies'); //проверить куки
Route::get('/get_home_tracks_count', 'SpotifyAPIController@getHomePageUserTracksCount'); //получить кол-во треков в библиотеке
Route::get('/get_spotify_username', 'SpotifyAPIController@getSpotifyUsername'); //получить имя пользователя
Route::get('/get_site_info', 'HomeController@getSiteInfo'); //получить информацию о сайте
Route::get('/get_about', 'HomeController@getAbout'); //получить информацию о сайте, about
Route::get('/get_faq', 'HomeController@getFAQ'); //получить информацию о сайте, faq
Route::get('/get_contacts', 'HomeController@getContacts'); //получить информацию о сайте, faq
Route::get('/get_logo_img', 'HomeController@getSiteLogoUrl'); //получить логотип сайта
Route::get('/get_home_page_img', 'HomeController@getHomePageImageUrl'); //получить фоновое изображение для дом. страницы
Route::get('/get_welcome_img', 'HomeController@getWelcomeImageUrl'); //получить картинку для приветствия

//Profile
//BasicStats
Route::get('/get_spotify_profile', 'SpotifyAPIController@getSpotifyProfile'); //получить профиль пользователя
Route::get('/get_spotify_user_library', 'SpotifyAPIController@getSpotifyUserLibrary'); //получить библиотеку пользователя целиком
Route::get('/get_spotify_tracks', 'SpotifyAPIController@getSpotifyTracks'); //получить кол-во треков и последние пять
Route::get('/get_spotify_albums', 'SpotifyAPIController@getSpotifyAlbums'); //получить кол-во альбомов и последние пять
Route::get('/get_spotify_artists', 'SpotifyAPIController@getSpotifyArtists'); //получить кол-во подписок и случайные пять
Route::get('/get_user_library_time', 'SpotifyAPIController@getUserLibraryTime'); //посчитать время библиотеки
Route::get('/get_five_tracks', 'SpotifyAPIController@getFiveLongestAndShortestTracks'); //получить пять самых длинных треков
Route::get('/get_average_track_length', 'SpotifyAPIController@getAverageLengthOfTrack'); //получить среднюю длину трека
Route::get('/generate_bg_image', 'SpotifyAPIController@generateBackgroundImage'); //генерация фонового изображения для профиля
Route::get('/get_favorite_genres', 'SpotifyAPIController@getFavoriteGenres'); //получить любимые жанры пользователя (10 шт.)
Route::get('/get_unique_artists', 'SpotifyAPIController@getUniqueArtists'); //посчитать исполнителей
Route::get('/get_years_and_decades', 'SpotifyAPIController@getYearsAndDecades'); //посчитать годы и десятилетия
Route::get('/get_decade_month', 'SpotifyAPIController@getDecadeMonth'); //посчитать десятилетия за месяц
//Top10
Route::get('/get_top10_tracks/{top10Type}', 'SpotifyAPIController@getTop10Tracks'); //получить топ 10 треков
Route::get('/get_top10_artists/{top10Artists}', 'SpotifyAPIController@getTop10Artists'); //получить топ 10 треков
Route::get('/get_top10_tracks_by_length/{top10Type}', 'SpotifyAPIController@getTop10TracksByLength'); //получить топ 10 самых длинных сили самых коротких треков
Route::get('/get_top10_tracks_by_popularity/{type}', 'SpotifyAPIController@getTop10TracksByPopularity'); //получить топ 10 треков по популярности
Route::get('/get_top10_artists_by_tracks', 'SpotifyAPIController@getTop10ArtistsByTracks'); //получить топ 10 артистов по кол-ву треков в библиотеке
Route::get('/get_top10_artists_by_time', 'SpotifyAPIController@getTop10ArtistsByTime'); //получить топ 10 артистов по кол-ву времени треков
//Achievements
Route::get('/get_most_listened_track/{type}', 'SpotifyAPIController@getMostListenedTrack'); //получить самый прослушиваемый трек за все время или за месяц
Route::get('/get_track_by_popularity/{type}', 'SpotifyAPIController@getTrackByPopularity'); //получить самый популярный или непопулярный трек из библиотеки
Route::get('/get_track_by_duration/{type}', 'SpotifyAPIController@getTrackByDuration'); //получить самый длинный или короткий трек из библиотеки
Route::get('/get_most_listened_artist/{type}', 'SpotifyAPIController@getMostListenedArtist'); //получить самого слушаемого исполнителя за все время или месяц
Route::get('/get_artist_by_tracks', 'SpotifyAPIController@getArtistByTracks'); //получить исполнителя с наибольшим кол-во треков
Route::get('/get_artist_by_time', 'SpotifyAPIController@getArtistByTime'); //получить исполнителя с наибольшим кол-вом времени треков
Route::get('/get_artist_by_popularity/{type}', 'SpotifyAPIController@getArtistByPopularity'); //получать самого популярного или непопулярного артиста из подписок
//RecentTracks
Route::get('/get_latest_tracks', 'SpotifyAPIController@getLatestTracks'); //получить последние полученные треки


//Playlists
Route::get('/create_playlist/{type}', 'SpotifyAPIController@createPlaylist');

Route::get('/clean_user_data', 'SpotifyAuthController@cleanUserData'); //очистить данные пользователя
?>