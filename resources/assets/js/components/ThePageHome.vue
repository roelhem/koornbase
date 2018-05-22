<template>

    <b-container>

        <b-row>

            <b-col lg="12" v-for="(color, index) in colors" :key="`color-${index}`">
                <div class="tags">
                    <base-tag :color="color" remove-button @remove-click="$set(colors, index, undefined)" label="NoAvatar" />
                    <base-tag :color="color" :avatar="{color:color}" label="Empty Avatar" />
                    <base-tag :color="color" :avatar="{color:color,letters:'RH'}" label="Avatar met letters" />
                    <base-tag :color="color" :avatar="{color:color,placeholder:true, colorInvert:true}" label="Placeholder Avatar" />
                    <base-tag :color="color" :avatar="{icon:['fa','fa-tag']}" label="Icon Avatar" />
                    <base-tag :color="color" :avatar="{image:'https://www.w3schools.com/howto/img_avatar.png'}" label="Image Avatar" />
                    <base-tag :color="color" rounded :avatar="{color:color,icon:['fe','fe-tag']}" label="Icon Avatar" />
                    <base-tag :color="color" rounded :avatar="{image:'https://www.w3schools.com/howto/img_avatar.png'}" label="Image Avatar" />
                    <base-tag :label="color || 'default'" />
                </div>
            </b-col>

        </b-row>

    </b-container>

</template>

<script>
    import BaseTag from "./BaseTag";
    import BaseAvatar from "./BaseAvatar";
    import { TAG_COLORS } from '../constants/style';

    export default {
        components: {
            BaseAvatar,
            BaseTag
        },
        name: "the-page-home",

        data:function() {
            return {
                colors: [false].concat(TAG_COLORS)
            }
        },

        methods:{
            removeClickHandler(event) {
                alert(event);
            }
        }

    }
</script>

<style scoped>

</style>