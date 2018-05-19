<template>
    <div class="map-header person-page-header" :style="bannerStyle"></div>
</template>

<script>
    export default {

        props: {
            person:Object,
            defaultBanner:{
                type: String,
                default:'https://upload.wikimedia.org/wikipedia/commons/7/76/Vleeshal_Delft.jpg',
            }
        },

        data:function() {
            return {
                banner: this.defaultBanner
            }
        },

        computed:{
            bannerStyle:function() {
                return {
                    'background-size': 'cover',
                    'background-position':'center',
                    'z-index':-1,
                    'background-image':'url('+this.banner+')',
                };
            }
        }



    }
</script>

<style scoped>

    .person-page-header {
        -webkit-background-size: cover;
        background-size: cover;
        background-position: center;
        z-index: -1;
        height:200px;
    }

</style>