//HomePage
<template>
    <div class="container">
        <router-view>
        </router-view>
        <!-- предупреждение о кукисах -->
        <Cookies />
    </div>
</template>

<script>
    export default {
        
        // хуки
        created(){
            this.checkToken ().then(response => {

                if(response === true || response === false)
                {
                    if(this.navSettings == -1)
                    { this.$store.dispatch('getNavSettings'); }

                    //получить фоновое изображение
                    if(this.homePageImageUrl == -1)
                    { this.$store.dispatch('getHomePageImageUrl'); }

                    //получить логотип сайта
                    if(this.siteLogoUrl == -1)
                    { this.$store.dispatch('getSiteLogoUrl'); }

                    //получить изображение для приветствия
                    if(this.welcomeImageUrl == -1)
                    { this.$store.dispatch('getWelcomeImageUrl'); } 

                    //получить информацию о сайте
                    this.$store.dispatch('getSiteInfo');

                    //получить приветственное сообщение
                    this.$store.dispatch('getWelcomeMessage'); 

                    //получить юзернейм пользователя
                    if(this.spotifyUsername == -1)
                    { this.$store.dispatch('getSpotifyUsername'); }
                
                    //получить кол-во треков в библиотеке для сообщения на главной странице
                    if(this.spotifyUserTracksCount == -1)
                    { this.$store.dispatch('getHomePageUserTracksCount'); }    
                }
            });
        },

        // данные
        data: () => {
            return {
                animationForLogo: true,
            }
        },

        computed: {
            //настройки навигации
            navSettings: function(){
                return this.$store.state.homePage.navSettings;
            },

            //фоновое изображение
            homePageImageUrl: function(){
                return this.$store.state.homePage.homePageImageUrl;
            },

            //ссылка на логотип сайта
            siteLogoUrl: function(){
                return this.$store.state.homePage.siteLogoUrl;
            },

            //изображение для приветствия
            welcomeImageUrl: function(){
                return this.$store.state.homePage.welcomeImageUrl;
            },

             //юзернейм пользователя
            spotifyUsername: function(){
                return this.$store.state.homePage.spotifyUsername;
            },
            //кол-во треков в библиотеке
            spotifyUserTracksCount: function(){
                return this.$store.state.homePage.spotifyUserTracksCount;
            },
        }
    }
</script>