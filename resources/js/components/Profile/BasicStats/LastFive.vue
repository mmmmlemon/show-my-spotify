//LastFive
// BasicStats.vue - общее кол-во треков \ альбомов \ подписок на артистов и пять случайных для отображения
<template>
    <div class="col-11 col-md-11 col-lg-4" style="margin-bottom: 1.5rem;">
        <div class="row justify-content-center">
            <!-- если не указан type -->
            <div v-if="type === false">
                <Error type="small" errorMessage="Не указан тип компонента" />
            </div>
            <!-- если указан type -->
            <div v-else>
                <!-- если запрос вернул false -->
                <div v-if="items == false">
                    <Error v-if="type === 'tracks'" type="small" errorMessage = "Не удалось загрузить данные по трекам"/>
                    <Error v-if="type === 'albums'" type="small" errorMessage = "Не удалось загрузить данные по альбомам"/>
                    <Error v-if="type === 'artists'" type="small" errorMessage = "Не удалось загрузить подписки"/>
                </div>
                <!-- если запрос не вернул false -->
                <div v-if="items != false">
                    <div v-if="items == -1">
                        <Loader />
                    </div>
                    <div v-else-if="items != -1" class="goUpAnimSlow paddingSides">
                        <h4 v-if="type === 'tracks'" class="text-center borderUnderline">
                            Треки - <b>{{items['count']}}</b>
                        </h4>
                        <h4 v-if="type === 'albums'" class="text-center borderUnderline">
                            Альбомы - <b>{{items['count']}}</b>
                        </h4>
                        <h4 v-if="type === 'artists'" class="text-center borderUnderline">
                            Подписки - <b>{{items['count']}}</b>
                        </h4>
                        <div v-if="items == false">
                            <Error v-if="type === 'tracks'" type="small" errorMessage = "Не удалось загрузить треки"/>
                            <Error v-if="type === 'albums'" type="small" errorMessage = "Не удалось загрузить альбомы"/>
                            <Error v-if="type === 'artists'" type="small" errorMessage = "Не удалось загрузить подписки"/>
                        </div>
                        <div v-else-if="items == -1">
                        </div>
                        <div v-else-if="items['lastFive'].length >= 0 && items['lastFive'].length != false" class="col-11 fadeInAnim">  
                                <div class="col-12">
                                    <p v-if="type === 'tracks'" class="text-center font10pt">Последние треки</p>
                                    <p v-if="type === 'albums'" class="text-center font10pt">Последние альбомы</p>
                                    <p v-if="type === 'artists'" class="text-center font10pt">Некоторые из твоих подписок</p>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-2 fadeInAnim" 
                                        v-for="(item, index) in items['lastFive']" :key="index">
                                        <a :href="item.url" target="_blank">
                                            <img class="rounded-circle albumIconSmall" :src="item.cover" 
                                                @mouseover="showTitle(item.name, true)" @mouseleave="showTitle('<p>Artist - Song Title <br> Artist - Song Title</p>', false)">
                                        </a>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 text-center lastFiveTitle" v-html="title"
                                        v-bind:class="{'zeroOpacity': !visible}">
                                    </div>
                                </div>
                        </div> 
                        <div v-else>
                                <div class="col-md-11">
                                    <p v-if="type === 'tracks'" class="text-center font10pt">Последние добавленные треки</p>
                                    <p v-if="type === 'albums'" class="text-center font10pt">Последние добавленные альбомы</p>
                                    <p v-if="type === 'artists'" class="text-center font10pt">Некоторые из твоих подписок</p>
                                </div>
                                <div class="row justify-content-center">
                                    <div>
                                        -
                                    </div>
                                </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>   
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            title: `<p>Artist - Song Title <br> Artist - Song Title</p>`,
            visible: false,
        }
    },
    props: {
        items: { default: -1 },
        type: { default: false, string: String},
    },

    methods: {
        showTitle(name, visibility){

            this.visible = visibility;
            this.title = `<p>${name}</p>`;
        },
    }

}
</script>