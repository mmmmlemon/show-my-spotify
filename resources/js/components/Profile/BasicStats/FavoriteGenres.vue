//FavoriteGenres
// BasicStats.vue - график любимых жанров
<template>
    <div class="col-12 col-md-12 fadeInAnim marginVertical" v-scroll="handleScroll" v-bind:class="{'zeroOpacity': visible === false}">
        <div class="row justify-content-center">
            <!-- лоадер -->
            <div v-if="favoriteGenres == -1">
                <Loader />
                <h6 class="text-center blinkingAnim">
                    Анализирую треки...
                </h6>
                <p class="text-center">
                    Это может занять некоторое время
                </p>
            </div>
            <!-- ошибка -->
            <div v-else-if="favoriteGenres == false">
                <Error type="small" errorMessage="Не удалось произвести анализ треков"/>
            </div>
            <!-- предупреждение -->
            <div v-else-if="favoriteGenres == 'noTracks'" class="col-12">
                <br>
            </div>
            <!-- контент -->
            <div v-else-if="favoriteGenres != -1 && favoriteGenres != false && visible === true" class="col-12 col-md-12 goUpAnimSlow">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <h5>А еще в последний месяц ты больше всего слушаешь</h5>
                    </div>
                    <!-- жанр 1 -->
                    <div class="col-12 col-md-3 paddingSides">
                        <h3 class="text-center borderUnderline mainColorHighlight2">
                            {{Object.keys(favoriteGenres)[0]}}
                        </h3>
                    </div>
                    <!-- жанр 2 -->
                    <div class="col-12 col-md-3 paddingSides">
                         <h3 class="text-center borderUnderline mainColorHighlight2">
                            {{Object.keys(favoriteGenres)[1]}}
                        </h3>
                    </div>
                    <!-- жанр 3 -->
                    <div class="col-12 col-md-3 paddingSides">
                         <h3 class="text-center borderUnderline mainColorHighlight2">
                            {{Object.keys(favoriteGenres)[2]}}
                        </h3>
                    </div>
                    <h5>А вот так выглядит весь список</h5>
                    <!-- график с жанрами -->
                    <div class="col-11" v-if="favoriteGenres !== -1 && favoriteGenres != false">
                        <BarChart :favoriteGenres="favoriteGenres" :backgroundColor="backgroundColor" :styles="style" label="Песен этого жанра за месяц"/>
                    </div>
                </div>
            
            </div>
            <!-- ошибка -->
            <div v-else>
                <Error type="small"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data: () => {
        return {
            //цвета для графика
            backgroundColor: ['#1b77b9','#1bb98a','#48b91b','#b9941b','#b91b1b','#b91bb1','#4a1bb9','#223586','#228638','#864f22'],
            style: {
                color: 'red',
            },
            visible: false,
        }
    },
    
    props: {
        //любимые жанры
        favoriteGenres: { default: -1 },
    },

    computed: {
        //видимость карточки
        setVisible: {
            get() {
                this.visible = false;
            },
            set(value){
                this.visible = value;
            }
        },
    },

    methods: {
        //при скролле страницы показать карточку когда она будет 
        //в поле видимости
        handleScroll: function (evt, el){
            if (el.getBoundingClientRect().top < 700) {
                this.setVisible = true;
            }
            return el.getBoundingClientRect().top < 700;   
        }
    }

}

</script>