// Welcome
// приветствие на главной странице
<template>
    <div class="container fadeInAnim">

        <div class="row justify-content-center">
            <!-- если пользователь не залогинен -->
            <div class="col-12 col-sm-12 col-md-10 col-lg-10" width="20%;">
                <div class="col-12">
                    <!-- название сайта -->
                    <transition name="siteTitle" v-on:before-enter="setLogoAnimation(true)">
                        <h2 class="text-center siteTitleHome" v-if="siteTitle && spotifyUsername == false">
                            {{siteTitle}}
                        </h2>
                    </transition>
                    <!-- логотип -->
                    <transition name="logo">
                        <div v-if="siteTitle && spotifyUsername == false">
                            <Logo :animation="logoAnimation"/>
                        </div>
                    </transition>
                    <!-- текст приветствия -->
                    <transition name="welcome">
                        <p v-if="welcomeMessage != false && spotifyUsername == false" 
                            v-html="welcomeMessage" class="pText text-center">
                        </p>
                    </transition>
                    
                    <transition name="welcome">
                        <hr v-if="welcomeMessage != false && spotifyUsername == false">    
                    </transition>

                    <!-- заманухи всякие -->
                    <div class="row justify-content-center text-center">
                        <transition name="artist">
                            <div class="col-4" v-if="welcomeMessage != false && spotifyUsername == false">
                                <h6><b>Кто ваш любимый артист?</b></h6>
                                <i class="fas fa-guitar homeIcon"></i>
                            </div>
                        </transition>
                        <transition name="track">
                            <div class="col-4" v-if="welcomeMessage != false && spotifyUsername == false">
                                <h6><b>Какую песню вы слушаете больше других?</b></h6>
                                <i class="fas fa-music homeIcon"></i>
                            </div>
                         </transition>
                        <transition name="decade" v-on:after-enter="setBgStyle">
                            <div class="col-4" v-if="welcomeMessage != false && spotifyUsername == false">
                                <h6><b>Какая ваша любимая эпоха в музыке?</b></h6>
                                <i class="fas fa-record-vinyl homeIcon"></i>
                            </div>
                        </transition>
                        <transition name="yeahboi">
                            <div class="col-12" v-if="welcomeMessage != false && spotifyUsername == false" style="margin-top:1.5rem;">
                                   <h5 >Все это, и не только, можно узнать тут!</h5>
                            </div>
                        </transition>
                    </div>

                    <transition name="welcome">
                        <hr v-if="welcomeMessage != false && spotifyUsername == false" style="margin-bottom: 3rem;">    
                    </transition>

                    <!-- кнопка "Войти" -->
                    <transition name="enterButton" v-on:after-enter="setLogoAnimation(false)">
                        <div class="row justify-content-center" v-if="welcomeMessage != false && spotifyUsername == false">
                            <div class="col-md-4 col-10 justify-content-center marginVertical">
                                <a href="/spotify_login" class="btn btn-primary-n btn-rounded btn-block">Войти через Spotify</a>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>

            <!-- если пользователь залогинен -->
            <div class="col-12 col-sm-12 col-md-10 col-lg-10" v-if="spotifyUsername != -1 && spotifyUsername != false">
                <div class="row justify-content-center">
                    <!-- приветствие для больших экранов -->
                    <div class="col-11 text-center d-none d-md-block fadeInAnim">
                        <h2 v-if="spotifyUsername != false" class="font4vw">
                            Привет, <b style="color: var(--main-color);">{{spotifyUsername}}</b>!
                        </h2>
                    </div>
                    <!-- для мобилок -->
                    <div class="col-11 text-center d-sm-block d-md-none fadeInAnim" v-if="spotifyUsername != false">
                        <h2 class="font6vw">
                            Привет, <b style="color: var(--main-color);">{{spotifyUsername}}</b>!
                        </h2>
                    </div>
                    
                    <!-- лоадер -->
                    <div class="container bounceInAnim" v-if="spotifyUserTracksCount == -1 && spotifyUsername != false">
                        <Loader/>
                    </div>

                    <!-- когда загрузится кол-во треков, показываем сообщение -->
                    <div v-if="spotifyUserTracksCount != -1" class="col-10 text-center fadeInAnim">
                        <!-- если треков больше 150 -->
                        <h3 v-if="spotifyUserTracksCount['trackCount'] >= 150">
                            В твоей библиотеке более чем достаточно треков для анализа <i class="fas fa-heart heartbeatAnim" style="color: var(--main-color-highlight);"></i>
                        </h3>
                        <!-- если треков больше или равно 50 -->
                        <h4 v-else-if="spotifyUserTracksCount['trackCount'] >= 50">
                            В твоей библиотеке достаточно треков для анализа! 😉
                        </h4>
                        <!-- если треков больше или равно 10 -->
                        <h4 v-else-if="spotifyUserTracksCount['trackCount'] >= 10">
                            Ай! Маловато будет! 🤔
                        </h4>  
                        <!-- если треков меньше 10 -->
                        <h4 v-else-if="spotifyUserTracksCount['trackCount'] < 10 && spotifyUserTracksCount['trackCount'] > 0">
                            Ой, что-то у тебя пусто...😳
                        </h4>              
                        <!-- если треков 0 -->
                        <h4 v-else-if="spotifyUserTracksCount['trackCount'] == 0">
                            Ни одной песни? bruh... 💩
                        </h4>
                        <h3 v-else></h3>
                        
                        <!-- сообщение если кол-во треков больше нуля, но меньше 50 -->
                        <h5 v-if="spotifyUserTracksCount['trackCount'] < 50 && spotifyUserTracksCount['trackCount'] > 0">
                            Слишком мало треков чтобы составить статистику. Добавь побольше песен в свою библиотеку (в библиотеке: {{spotifyUserTracksCount['trackCount']}}, нужно: 50).
                        </h5>
                        <!-- сообщение если треков - ноль -->
                        <h5 v-else-if="spotifyUserTracksCount['trackCount'] == 0">
                            Ни одной песни в библиотеке. Добавь их побольше (нужно: 50).
                        </h5>
                        <!-- ссылка на профиль -->
                        <h5 v-else class="fadeInAnimSlow">
                            Перейди в <router-link to="/profile" class="borderUnderline">свой профиль</router-link> чтобы просмотреть статистику
                        </h5>
                    </div>
                </div>

                <!-- случайные обложки альбомов -->
                <div class="text-center" style="margin-top: 6rem;" v-if="spotifyUserTracksCount !== -1 && spotifyUserTracksCount['trackCount'] >= 50">
                    <div class="row justify-content-center" style="margin-left: 5%;">
                        <div class="col-2">
                            <img :src="spotifyUserTracksCount.trackCovers[0]" class="img-fluid rounded-circle albumCoverHome fadeInCover1">
                        </div>
                        <div class="col-2">
                            <img :src="spotifyUserTracksCount.trackCovers[1]" class="img-fluid rounded-circle albumCoverHome fadeInCover2">
                        </div>
                        <div class="col-2">
                            <img :src="spotifyUserTracksCount.trackCovers[2]" class="img-fluid rounded-circle albumCoverHome fadeInCover3">
                        </div>
                        <div class="col-2">
                            <img :src="spotifyUserTracksCount.trackCovers[3]" class="img-fluid rounded-circle albumCoverHome fadeInCover4">
                        </div>
                        <div class="col-2">
                            <img :src="spotifyUserTracksCount.trackCovers[4]" class="img-fluid rounded-circle albumCoverHome fadeInCover5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        // хуки
        mounted(){
            this.visible = true;
        },

        // данные
        data(){
            return {
                welcomeImgLoaded: false,
                logoAnimation: false,
                bgStyle: 'backgroundImage invisible',
                visible: false,
            }
        },

        computed: {
            //название сайта
            siteTitle: function(){
                return this.$store.state.homePage.siteInfo['siteTitle'];
            },
            //welcome message
            welcomeMessage: function(){
                return this.$store.state.homePage.welcomeMessage;
            },
            //юзернейм пользователя
            spotifyUsername: function(){
                return this.$store.state.homePage.spotifyUsername;
            },
            //кол-во треков в библиотеке
            spotifyUserTracksCount: function(){
                return this.$store.state.homePage.spotifyUserTracksCount;
            },
            //ссылка на логотип сайта
            siteLogoUrl: function(){
                return this.$store.state.homePage.siteLogoUrl;
            },
            //фоновое изображение
            homePageImageUrl: function(){
                return this.$store.state.homePage.homePageImageUrl;
            },
            //изображение для приветствия
            welcomeImageUrl: function(){
                return this.$store.state.homePage.welcomeImageUrl;
            },

            //анимация для логотипа
            animationForLogo: function(){
                return this.$parent.animationForLogo;  
            },
        },

        // методы
        methods: {
            onWelcomeImgLoad(){
                this.welcomeImgLoaded = true;
            },
            setLogoAnimation(value){
                this.logoAnimation = value;
            },
            setBgStyle(){
                this.bgStyle = 'backgroundImage fadeInAnimBg';
            },
        },

    }
</script>