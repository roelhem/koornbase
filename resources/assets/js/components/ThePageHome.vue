<template>

    <div>

        <b-container>

            <b-row>

                <b-col v-for="styleCard in styleCards" :key="'style-card-'+styleCard.style.name" lg="4">

                    <show-style-card :subject-style="styleCard.style" :collapsed.sync="styleCard.collapsed" />

                </b-col>

            </b-row>

        </b-container>
    </div>


</template>

<script>

    import ALL_STYLES from "../constants/styles";
    import ShowStyleCard from "./ShowStyleCard";

    export default {
        components: {
            ShowStyleCard,
        },
        name: "the-page-home",

        data:function() {
            return {
                styleCards: ALL_STYLES.map(el => {
                    return {
                        style: el,
                        collapsed: false
                    };
                }),
            }
        },

    }
</script>

<style scoped>

</style>