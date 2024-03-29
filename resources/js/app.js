
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import axios from 'axios'
import VueAxios from 'vue-axios'
import store from './store';

import Error from './components/Misc/Error.vue';
Vue.component('Error', Error);
import Loader from './components/Misc/Loader.vue';
Vue.component('Loader', Loader);
import Info from './components/Misc/Info.vue';
Vue.component('Info', Info);
import BackgroundImage from './components/Misc/BackgroundImage.vue';
Vue.component('BackgroundImage', BackgroundImage);
import Logo from './components/Misc/Logo.vue';
Vue.component('Logo', Logo);
import Avatar from './components/Misc/Avatar.vue';;
Vue.component('Avatar', Avatar);

import LastFive from './components/Profile/BasicStats/LastFive.vue';
Vue.component('LastFive', LastFive);
import HoursAndMinutes from './components/Profile/BasicStats/HoursAndMinutes.vue';
Vue.component('HoursAndMinutes', HoursAndMinutes);
import AverageTrackLength from './components/Profile/BasicStats/AverageTrackLength.vue';
Vue.component('AverageTrackLength', AverageTrackLength);
import FavoriteGenres from './components/Profile/BasicStats/FavoriteGenres.vue';
Vue.component('FavoriteGenres', FavoriteGenres);
import ArtistsCount from './components/Profile/BasicStats/ArtistsCount.vue';
Vue.component('ArtistsCount', ArtistsCount);
import YearsAndDecades from './components/Profile/BasicStats/YearsAndDecades.vue';
Vue.component('YearsAndDecades', YearsAndDecades);

import Top10Items from './components/Profile/Top10/Top10Items.vue';
Vue.component('Top10Items', Top10Items);

import AchievementItem from './components/Profile/Achievements/AchievementItem.vue';
Vue.component('AchievementItem', AchievementItem);

import List from './components/Profile/Latest/List.vue';
Vue.component('List', List);

import ListItem from './components/Profile/Latest/ListItem.vue';
Vue.component('ListItem', ListItem);

import Cookies from './components/Misc/Cookies.vue';
Vue.component('Cookies', Cookies);

//графики
import BarChart from './components/Charts/BarChart.vue';
Vue.component('BarChart', BarChart);

//Навигация
import Navigation from './components/Nav/Navigation.vue';
Vue.component('Navigation', Navigation);


Vue.use(VueAxios, axios);

import router from './router';
import Vue from 'vue';

//перед перезагрузкой страницы, или перед выходом с сайта
//отправляем api-запрос на удаление папки с файлами пользователя
window.addEventListener("beforeunload", function(evt) {
    axios.get('/api/clean_user_data').then(response => {
        console.log("%cTemporary user data has been removed.", 'font-weight: bold;')
    });
});

Vue.directive('scroll', {
    inserted: function (el, binding) {
      let f = function (evt) {
        if (binding.value(evt, el)) {
          window.removeEventListener('scroll', f)
        }
      }
      window.addEventListener('scroll', f)
    }
  });

//плагин для проверки токена
const CheckTokenPlugin = {
    install(Vue, options) {
      Vue.prototype.checkToken = () => {
        
        //проверяем есть ли токен вообще
        return axios.get('/api/check_token').then(response => {
           if(response.data == 'yesToken'){
             return true;
           } else if(response.data == 'refreshToken'){
              var url = window.location.href;
              var indexOfAnchor = url.indexOf('#');
              if(indexOfAnchor != -1)
              {var url = url.slice(0, indexOfAnchor);}
              axios.get('/refresh_token').then(response => {
                  if(response.data = true){
                      
                      window.location.replace(url);
                  }
              });
           } else{
             //if 'noToken'
             return false;
           }
        });

      }
    },
  }

  Vue.use(CheckTokenPlugin)



const app = new Vue({
    store,
    el: '#app',
    router
});