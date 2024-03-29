<?php

namespace App\Globals;
use Auth;
use Carbon\Carbon;
use SpotifyWebAPI;
use Cookie;
use File;
use Image;

//Helpers
//глобальные функции на разные случаи
class Helpers
{
        //pickTheWord
        //выбрать правильное окончание для словосочетания (десять минуТ, две минуТЫ, одна минуТА и т.п)
        //параметры: число для которого нужно подобрать слово с нужным окончанием, 
        //первый вариант окончания (firstWord, 5 минуТ, 0 минуТ, 10 минуТ, 11-19 минуТ), 
        //второй вариант окончания (secondWord, 1 минуТА), 
        //третий вариант окончания (thirdWord, 2, 3, 4 минуТЫ)
        //возвращает подходящее слово из трех, либо false если в параметре $number не было указано число
        public static function pickTheWord($number, $firstWord, $secondWord, $thirdWord)
        {
            //проверяем содержит ли $number число
            //если нет, то возвращаем false
            if(is_numeric($number) == false)
            { return response()->json(false); }
            else
            {
                //получаем последнюю цифру в числе
                $lastNumber = strval($number)[strlen(strval($number)) - 1];

                //если число двузначное или больше
                //получаем последние два числа на случай если там будет число от 11 до 19
                $lastTwoNumbers = "";

                if(strlen(strval($number)) >= 2)
                { $lastTwoNumbers = strval($number)[strlen(strval($number)) - 2] . strval($number)[strlen(strval($number)) - 1]; }

                //если число от 11-ти до 19-ти, то возвращаем firstWord
                if(intval($lastTwoNumbers) >= 10 && intval($lastTwoNumbers) <= 19)
                { return $firstWord; }
                else
                {
                    //если число заканчивается на 1, то возвращаем secondWord
                    if($lastNumber == "1")
                    { return $secondWord; }
                    //если заканчивается на 2, 3 или 4, то возвращаем thirdWord
                    elseif($lastNumber == "2" || $lastNumber == "3" || $lastNumber == "4")
                    { return $thirdWord; }
                    //в иных случаях возвращаем firstWord
                    else
                    { return $firstWord; }
                }
            }
        }

        //getFullNameOfItem
        //получить полное название трека или альбома 
        //параметры: track - трек из SpotifyAPI, type - artist или fullname
        //возвращает строку с полным названием, либо false
        public static function getFullNameOfItem($item, $type = "fullname")
        {   
            //проверяем существуют ли параметры artists и name
            if(property_exists($item, 'artists') && property_exists($item, 'name'))
            {
                //если артистов больше нуля
                if(count($item->artists) > 0)
                {
                    $artists = "";
                
                    //записываем имена артистов через запятую
                    for($i = 1; $i <= count($item->artists); $i++)
                    {
                        if($i != count($item->artists) && count($item->artists) > 1)
                        { $artists .= $item->artists[$i-1]->name . ", ";}
                        else
                        { $artists .= $item->artists[$i-1]->name; }
                    }

                    //если type - artist, то возвращаем список артистов
                    if($type == "artist")
                    {
                        return $artists;
                    }
                    //если type - fullname, то возвращаем
                    else if($type == "fullname")
                    { return $artists . " - " . $item->name; }
                    //иначе возвращаем false
                    else
                    { return response()->json(false);  }
                    
                }
                else
                { return response()->json(false); } 
            }
            //если параметров artists и name нет, то возвращаем false
            else
            { return response()->json(false); }
        }

        //sortFunction
        //функция сортировки по ключу, по убыванию или по возрастанию
        //параметры: ключ и порядок сортировки
        //возвращает отсортированные массив, либо false если не был задан верный порядок сортировки
        private static function sortFunction($key, $order) 
        {
            if($order == "asc") //если порядок - по возрастанию
            {
                return function ($a, $b) use ($key) {
                    return strnatcmp($a[$key], $b[$key]);
                };
            }
            else if($order == "desc") //если порядок - по убыванию
            {
                return function ($a, $b) use ($key) {
                    return strnatcmp($b[$key], $a[$key]);
                };
            }
            else
            { return false; } 
        }

        //sortArrayByKey
        //сортировка массива по ключу
        //параметры: массив, ключ, порядок сортировки
        public static function sortArrayByKey($array, $key, $order)
        {
            $arrayCopy = $array;

            usort($arrayCopy, Helpers::sortFunction($key, $order));

            return $arrayCopy;
        }

        //randomHslColor
        //сгенерировать случайный цвет в формате HSL
        //параметр: сдвиг по оси цветов
        //возвращает строку с цветом в формате HSL
        public static function randomHslColor($min, $max)
        {   
            //генерируем цвет в RGB, добавляем offset в начало
            $randNum = rand($min, $max);

            //записываем в строку, параметры saturation, brightness и opacity 
            //это параметры будут всегда одинаковые чтобы цвет был яркий
            $hslColor = "hsla(".$randNum.",100%,34%,1)";

            return $hslColor;
        }
            
        //trackDuration
        //перевести длительность трека из миллисекунд в минуты и секунды
        //возвращает строку с длиной трека в минутах и секундах
        //параметры: длина трека в миллисекундах
        public static function getTrackDuration($durationMs)
        {
            //проверяем явялется ли durationMs - числовым
            if(is_numeric($durationMs))
            {   
                //получаем длительность в минутах (мс / 1000 / 60, округляем до пяти знаков после запятой)
                $durationMinutes= round($durationMs / 1000 / 60, 5);

                //получаем длительность в часах ( мин / 60, округляем до пяти знаков после запятой)
                $durationHours = round($durationMinutes / 60, 5);

                //минуты из остатка часов (60 * часы - остаток)
                $durationMinutes = 60 * ($durationHours - floor($durationHours));

                //секунды из остатка минут (60 * минуты - остаток)
                $durationSeconds = floor(60 * ($durationMinutes - floor($durationMinutes)));

                $hoursStr = "";
                $minutesStr = "";
                $secondsStr = "";
                $durationStr = "";

                //получаем строковые значения часов, минут и секунд
                $hoursStr = strval(floor($durationHours));
                $minutesStr = strval(floor($durationMinutes));
                $secondsStr = strval($durationSeconds);
                
                //записываем время в строку
                if(strlen($hoursStr) == 1 && floor($durationHours) > 0)
                { $hoursStr = "0" . $hoursStr . ":"; }
                else if (floor($durationHours) == 0)
                { $hoursStr = ""; }
                else
                {  $hoursStr .= ":"; }

                if(strlen($minutesStr) == 1)
                { $minutesStr = "0" . $minutesStr; }

                if(strlen($secondsStr) == 1)
                { $secondsStr = "0" . $secondsStr; }

                $durationStr = $hoursStr.$minutesStr.":".$secondsStr;
                
                return $durationStr;
            }
            else
            { return false;  }

        }

        //getDurationInHours
        //получить длительность в часах из миллисекунд
        //возвращает строку с длительностью в часах
        //параметры: длительность в милисекундах
        public static function getDurationInHours($durationMs)
        {
            //проверяем является ли durationMs числом
            if(is_numeric($durationMs))
            {
                //получить минуты из миллисекунд (мс / 1000 / 60, округлить до пяти знаков после запятой)
                $durationMinutes = round($durationMs / 1000 / 60, 5);

                //получаем длительность в часах ( мин / 60, округляем до пяти знаков после запятой)
                $durationHours = round($durationMinutes / 60, 5);

                //минуты из остатка часов (60 * часы - остаток)
                $durationMinutes = 60 * ($durationHours - floor($durationHours));

                //секунды из остатка минут (60 * минуты - остаток)
                $durationSeconds = floor(60 * ($durationMinutes - floor($durationMinutes)));

                $hoursStr = "";
                $minutesStr = "";
                $secondsStr = "";
                $durationStr = "";

                //получаем строковые значения часов, минут и секунд
                $hoursStr = strval(floor($durationHours));
                $minutesStr = strval(floor($durationMinutes));
                $secondsStr = strval($durationSeconds);
                
                //записываем время в строку
                $durationStr = $hoursStr." ч. ".$minutesStr." мин. ".$secondsStr. " с.";
            
                return $durationStr;
            }
            else
            { return false; }
        }

        //getItemReleaseDate
        //получить дату выхода трека или альбома в виде года или полной даты
        //возвращает строку с датой выхода
        //параметры: трек или альбом из Spotify API, строка указание на то трек это или альбом, 
        //строка указание на тип даты: год или полная дата
        public static function getItemReleaseDate($item, $itemType = "track", $dateType = "long")
        {
            if(property_exists($item, "album") || property_exists($item, "release_date") || property_exists($item, "release_date_precision"))
            {
                $date = ""; //дата
                $releaseDatePrecision  = ""; //тип даты, год или полная дата 

                //записываем дату и тип даты из объекта
                if($itemType == "track")
                {
                    //получить дату из альбома
                    $date = $item->album->release_date;
                    $releaseDatePrecision = $item->album->release_date_precision;
                }
                else if ($itemType == "album")
                {
                    //получить дату
                    $date = $item->release_date;
                    $releaseDatePrecision = $item->release_date_precision;
                }
                else
                { return false; }
                
                //если dataType - short, то возвращаем год
                if($dateType == "short")
                {
                    //если дата рализа указана с точностью до дня или до месяца, то вырезаем из даты год
                    if($releaseDatePrecision == "day" || $releaseDatePrecision == "month")
                    { $date = substr($date, 0, 4); }
                    //если нет, то просто возвращаем дату
                    return $date;
                }
                //если dataType - long, то возвращаем полную дату
                else if ($dateType == "long")
                { return $date; }
                else
                { return false; }   
            }
            else
            { return false; }
        }

        //getArtistsGenres
        //получает список жанров артиста
        //возвращает строку со списком жанров артиста
        //параметры: артист и кол-во жанров которые нужно вывести
        public static function getArtistsGenres($artist, $count = 5)
        {
            if(property_exists($artist, "genres"))
            {
                $genres = "";
                //считаем сколько всего жанров записано у артиста
                $genresMax = count($artist->genres);

                //кол-во жанров которые нужно получить, по умолчанию равно параметру count
                $iMax = $count;

                //если кол-во жанров записанных у артиста меньше чем count, то iMax будет равно genresMax
                if($genresMax < $count)
                {  $iMax = $genresMax; }
                
                //записываем жанры в строку через цикл
                for($i = 0; $i < $iMax; $i++)
                {
                    $genres .= $artist->genres[$i];
                    if($i != $iMax - 1)
                    { $genres .=", "; }
                }

                return $genres;
            }
            else
            { return false; }
        }

        //randomNumbers
        //сгенерировать последовательность из N случайных неповторяющихся чисел
        //возвращает массив из чисел
        //параметры: мин. знач., макс. знач., кол-во значений
        public static function randomNumbers($min, $max, $quantity) {
            $numbers = range($min, $max);
            shuffle($numbers);
            return array_slice($numbers, 0, $quantity);
        }

        //checkCountries
        //проверить доступность альбома в странах
        //возващает true, если доступен в нужных странах, или false если не доступен хотя бы в одной стране
        //параметр: $album (SpotifyAPI Object) - альбом
        public static function checkCountries($album){
            $markets = $album->available_markets;
            $checkRussia = array_search('RU', $markets);
            $checkUkraine = array_search('UA', $markets);
            $checkBelarus = array_search('BY', $markets);
            $checkKazakhstan = array_search('KZ', $markets);

            if($checkRussia !== false && $checkUkraine !== false && $checkBelarus !== false && $checkKazakhstan !== false){
                return true;
            } else {
                return false;
            }
        }

        // createPlaylistCover
        // сгенерировать обложку для плейлиста
        // возвращает String с base64 сгенерированного изображения
        // параметры: $type (String) - тип плейлиста, $coverType (String) - тип обложки, для плейлиста по трекам или по артистам,
        //$data (Array) - массив с треками или артистами из которого будет браться обложка
        public static function createPlaylistCover($type, $coverType, $data)
        {
     
            $templateImgPath = null;

            // получаем шаблон обложки плейлиста в соотв. с типом 
            switch($type)
            {
                case 'top50alltime':
                    $templateImgPath = storage_path('app/public/system/top50alltime.png');
                    break;
                case 'top20month':
                    $templateImgPath = storage_path('app/public/system/top20month.png');
                    break;
                case 'top30long':
                    $templateImgPath = storage_path('app/public/system/top30long.png');
                    break;
                case 'top30short':
                    $templateImgPath = storage_path('app/public/system/top30short.png');
                    break;
                case 'top30popular':
                        $templateImgPath = storage_path('app/public/system/top30popular.png');
                        break;
                case 'top30unpopular':
                    $templateImgPath = storage_path('app/public/system/top30unpopular.png');
                    break;
                case 'artistsAlltime':
                        $templateImgPath = storage_path('app/public/system/artistsAlltime.png');
                        break;
                case 'artistsMonth':
                    $templateImgPath = storage_path('app/public/system/artistsMonth.png');
                    break;
                case 'artistsByLikes':
                    $templateImgPath = storage_path('app/public/system/artistsByLikes.png');
                    break;
                default:
                    $templateImgPath = storage_path('app/public/system/playlist.png');
                    break;
            }

            // создаем Image шаблон
            $templateImg = Image::make($templateImgPath)->resize(500,500);

            $coverImgPath = null;
            $brightness = 0;

            // получаем случайное изображении в соотв. с переданным массивом
            switch($coverType)
            {
                case 'tracks':
                    $coverImgPath = $data[rand(0,count($data)-1)]['album']->images[0]->url;
                    $brightness = -28;
                    break;
                case 'artists':
                    $coverImgPath = $data[rand(0,count($data)-1)]->images[0]->url;
                    break;
                    $brightness = -18;
            }

            $coverImg = Image::make($coverImgPath)->resize(500,500);
            $coverImg->brightness($brightness);

            // совмещаем шаблон и случайное изображение, шаблон накладывается поверх
            $coverImg->insert($templateImg, 'top-left');

            // генерируем base64 и убираем лишнее через regexp
            $base64 = preg_replace('#data:image/[^;]+;base64,#', '', $coverImg->encode('data-url')->encoded);

            return $base64;
        }


        //getTracksForPlaylist
        //получить треки для плейлиста
        // возвращает Array с ключами 'tracks' и 'cover', где 'tracks' - вложенный массив с id треков, а
        // 'cover' - base64 обложки для плейлиста
        // параметры: $request(Request), $type (String) - тип плейлиста
        public static function getTracksForPlaylist($request, $type)
        {

            $checkToken = System::setAccessToken($request);

            $tracks = null;

            if($checkToken != false)
            {

                $api = config('spotify_api');

                // если тип плейлиста "50 треков за всё время" или "20 треков за месяц"
                if($type === 'top50alltime' || $type === 'top20month')
                {
                    $options = null;

                    switch($type)
                    {
                        case 'top50alltime':
                            $options = ['time_range' => 'long_term', 'limit' => 50];
                            break;
                        case 'top20month':
                            $options = ['time_range' => 'short_term', 'limit' => 20];
                            break;
                    }

                    $tracks = $api->getMyTop('tracks', $options);
                    
                    //получаем только id треков и информацию об альбоме
                    $filtered = [];

                    foreach($tracks->items as $track){
                        array_push($filtered, ['id'=>$track->id, 'album'=>$track->album]);
                    }
                    
                    //создаём картинку для плейлиста
                    $cover = Helpers::createPlaylistCover($type, "tracks", $filtered);

                    $trackIds = [];

                    foreach($filtered as $track){
                        array_push($trackIds, $track['id']);
                    }
                    
                    return ['tracks'=> $trackIds, 'cover' => $cover];
                }
                // если тип плейлиста "30 самых длинных\коротких"
                else if($type === 'top30long' || $type === 'top30short')
                {
                    // получаем все треки
                    $tracks = System::getUserLibraryJson("tracks", $request);

                    // получаем id, album и duration_ms у треков
                    $filtered = [];

                    foreach($tracks as $track){
                        array_push($filtered, ['id' => $track->id, 'duration' => $track->duration_ms, 'album' => $track->album]);
                    }
                    
                    //в зав. от типа плейлиста сортируем по ключу duration по возрастанию или убыванию
                    switch($type){
                        case 'top30long':
                            $filtered = Helpers::sortArrayByKey($filtered, 'duration', 'desc');
                            break;
                        case 'top30short':
                            $filtered = Helpers::sortArrayByKey($filtered, 'duration', 'asc');
                    } 
                    
                    // берем первые 30 элементов и забираем только id и album
                    $filteredSecond = [];
                    
                    for($i = 1; $i <= 30; $i++){
                        array_push($filteredSecond, ['id' => $filtered[$i-1]['id'], 'album' => $filtered[$i-1]['album']]);
                    }
                    
                    // генерируем обложку и записываем id треков
                    $cover = Helpers::createPlaylistCover($type, "tracks", $filteredSecond);

                    $trackIds = [];
                    foreach($filteredSecond as $track){
                        array_push($trackIds, $track['id']);
                    }

                    return ['tracks'=> $trackIds, 'cover' => $cover];

                } 
                // если тип плейлиста "30 самых популярных\непопулярных"
                else if($type == 'top30popular' || $type == 'top30unpopular')
                {
                    // получаем все треки
                    $tracks = System::getUserLibraryJson("tracks", $request);

                    // берем id, popularity и album
                    $filtered = [];

                    foreach($tracks as $track){
                        if(count($track->album->available_markets) > 0)
                        {array_push($filtered, ['id' => $track->id, 'popularity' => $track->popularity, 'album' => $track->album]); }
                    }
                    
                    // сортируем по возрастанию или по убыванию в соотв. с типом плейлиста
                    switch($type){
                        case 'top30popular':
                            $filtered = Helpers::sortArrayByKey($filtered, 'popularity', 'desc');
                            break;
                        case 'top30unpopular':
                            $filtered = Helpers::sortArrayByKey($filtered, 'popularity', 'asc');
                    } 
                    
                    // берем первые 30 элементов
                    $filteredSecond = [];

                    for($i = 1; $i <= 30; $i++){
                        array_push($filteredSecond,  ['id' => $filtered[$i-1]['id'], 'album' => $filtered[$i-1]['album']]);
                    }   
                    
                    // генерируем обложку и записываем id треков
                    $cover = Helpers::createPlaylistCover($type, "tracks", $filteredSecond);

                    $trackIds = [];

                    foreach($filteredSecond as $track){
                        array_push($trackIds, $track['id']);
                    }
                  
                    return ['tracks'=> $trackIds, 'cover' => $cover];
                } 
                // если тип плейлиста "Артисты за всё время\месяц"
                else if($type == 'artistsAlltime' || $type == 'artistsMonth')
                {
                    $options = null;
                    switch($type){
                        case 'artistsAlltime':
                            $options = ['time_range' => 'long_term', 'limit' => 10];
                            break;
                        case 'artistsMonth': 
                            $options = ['time_range' => 'short_term', 'limit' => 10];
                            break;
                    }
                    
                    // получаем топ 10 артистов
                    $top10Artists = $api->getMyTop('artists', $options);

                    $tracksForPlaylist = [];
                    //для каждого артиста делаем следующее
                    foreach($top10Artists->items as $artist)
                    {   
                        //получаем список всех альбомов
                        $allAlbums = [];    
                        $offset = 0;
                        $options = ['limit' => 50, 'offset' => $offset];
                        $allAlbums = [];
                        $getAlbums = $api->getArtistAlbums($artist->id, $options)->items;
                        
                        while(count($getAlbums) > 0){
                            foreach($getAlbums as $album){

                                $checkCountries = Helpers::checkCountries($album);

                                //выбираем только альбомы (т.е не синглы и не сборники), только
                                //те что доступны в странах СНГ, в которых работает Spotify
                                //и только те, у которых указан один автор
                                if($album->album_group === 'album' && $album->album_type === 'album' &&
                                    $checkCountries == true && count($album->artists) == 1)
                                array_push($allAlbums, $album);
                            }
                            $options['offset'] += 50;
                            $getAlbums = $api->getArtistAlbums($artist->id, $options)->items;
                            
                        }
                        
                        //если альбомов больше 20-ти
                        //выбираем случайные десять и работаем с ним
                        if(count($allAlbums) >= 7)
                        {   
                            $gap = 10;
                            if(count($allAlbums) >= 7 && count($allAlbums) < 20)
                            { $gap = 7; }
                            // shuffle($allAlbums);
                            // shuffle($allAlbums);
                            $gapBegins = rand(0, count($allAlbums) - ($gap + 1));
                            $gapEnds = $gapBegins + 10;
                            $albumGap = array_slice($allAlbums, $gapBegins, $gap);
                        
                            //перемешиваем альбомы и берем первые пять
                            shuffle($albumGap);

                            $fiveAlbums = array_slice($albumGap, 0, 7);
                             
                            //для каждого из пяти альбомов выдергиваем один трек  
                            foreach($fiveAlbums as $album){
                                $getTracks = $api->getAlbumTracks($album->id)->items;
                                //берем только треки у которых длина 
                                //2 минуты или больше (120000 мс)
                                $allTracks = [];
                                foreach($getTracks as $track)
                                {
                                    if($track->duration_ms >= 120000){
                                        array_push($allTracks, $track);
                                    }
                                }
                                if(count($allTracks) != 0)
                                {
                                    $randNum = rand(0, count($allTracks)-1);
                                    $randTrack = $allTracks[$randNum];
                                    array_push($tracksForPlaylist, $randTrack->id);
                                }
                            }

                        }
                        //если альбомов меньше 7-ми
                        else if(count($allAlbums) < 7){
                            //получаем все треки из альбомов и выбираем случайные пять
                            shuffle($allAlbums);
                            $allTracks = [];

                            foreach($allAlbums as $album){
                                $getTracks = $api->getAlbumTracks($album->id)->items;
                                foreach($getTracks as $track){
                                    if($track->duration_ms > 120000){
                                        array_push($allTracks, $track);
                                    }
                                }
                            }

                            //если общее кол-во треков больше или равно пяти, то 
                            //выбираем треки
                            if(count($allTracks) >= 7){
                                $tracks = [];
                                while(count($tracks) != 7 )
                                {
                                    $rand = rand(0, count($allTracks) - 1);
                                    $randTrackId = $allTracks[$rand]->id;

                                    array_push($tracks, $randTrackId);

                                    unset($allTracks[$rand]);
                                    $allTracks = array_values($allTracks);
                                
                                }

                                $tracks = array_values(array_unique($tracks));

                                foreach($tracks as $track){
                                    array_push($tracksForPlaylist, $track);
                                }
                            }
                          
                        }
                        //если альбомов ноль
                        //такое бывает если исполнитель был удален из библиотеки Spotify
                        else if (count($allTracks) == 0)
                        { /*do nothing*/ }
                        else
                        { /*do nothing*/ }
                    }

                    $cover = Helpers::createPlaylistCover($type, "artists", $top10Artists->items);
                    return ['tracks' => $tracksForPlaylist, 'cover' => $cover];
                } 
                // если тип плейлиста "Артисты по лайкам"
                else if ($type == 'artistsByLikes'){
                    $tracks = System::getUserLibraryJson("tracks", $request);
                    
                        //если он есть
                        if($tracks != false)
                        {  
                            $artists = [];

                            //получаем все id исполнителей из списка треков
                            foreach($tracks as $track)
                            {
                                foreach($track->artists as $artist)
                                { array_push($artists, $artist->id); }
                            }

                            $artistsCount = [];

                            //считаем сколько раз встречается каждый id
                            foreach($artists as $artist)
                            {
                                if(array_key_exists($artist, $artistsCount) === false)
                                { $artistsCount[$artist] = 1; }
                                else
                                { $artistsCount[$artist] += 1; }
                            }
                        }

                        //сортировка по убыванию
                        arsort($artistsCount);

                        //берем первые десять
                        $top10Artists = array_slice(array_keys($artistsCount), 0, 10);

                        //список всех треков по исполнителям
                        $tracksByArtists = [];
                        
                        foreach($top10Artists as $artist){
                            $tracksByArtists[$artist] = [];
                            foreach($tracks as $track){
                                $artistId = $track->artists[0]->id;
                                if($artist === $artistId)
                                { array_push($tracksByArtists[$artistId], $track->id); }
                            }
                        }

                        //выбираем случайные треки
                        $tracksForPlaylist = [];
                        foreach($tracksByArtists as $tracks){
                            //если треков больше семи
                            if(count($tracks) > 7){

                                for($i = 1; $i <= 7; $i++)
                                {
                                    $rand = rand(0, count($tracks) - 1);

                                    array_push($tracksForPlaylist, $tracks[$rand]);
                                    unset($tracks[$rand]);
                                    $tracks = array_values($tracks);
                                }

                            } else if (count($tracks) <= 7){
                                foreach($tracks as $track){
                                    array_push($tracksForPlaylist, $track);
                                }
                            }
                        }    

                        // создаем массив артистов для генерации обложки
                        $artistsForCover = [];
                        foreach($top10Artists as $artist){
                            $artistData = $api->getArtist($artist);
                            array_push($artistsForCover, $artistData);
                        }

                        // генерируем обложки
                        $cover = Helpers::createPlaylistCover($type, "artists", $artistsForCover);

                        return ['tracks' => $tracksForPlaylist, 'cover' => $cover];
                }
            else
            { 
                return response()->json(false);
            }

        }
    }
}
        