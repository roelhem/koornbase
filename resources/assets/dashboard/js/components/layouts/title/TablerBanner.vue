<template>

    <div class="map-header" :style="bannerStyle"></div>

</template>

<script>
    export default {

        name: "tabler-banner",

        props: {
            src: {
                type: String,
                default: "https://upload.wikimedia.org/wikipedia/commons/7/76/Vleeshal_Delft.jpg"
            },
            height: {
                type: String,
                default: "200px",
            }
        },

        computed: {
            bannerStyle:function() {
                return {
                    'background-size':'cover',
                    'background-position':'center',
                    'z-index':-1,
                    'height':this.height,
                    'background-image':'url('+this.src+')'
                };
            }
        }

    }
</script>

<style scoped>

</style>