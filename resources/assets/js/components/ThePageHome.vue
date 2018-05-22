<template>

    <b-container>

        <b-row>

            <b-col lg="4">

                <div v-for="option in options" :key="'switch-div-'+option.name">
                    <form-switch :value="option.name" v-model="val" :color="option.color">
                        {{ option.label }}
                    </form-switch>
                </div>

            </b-col>

            <b-col>

                <pre>{{ val }}</pre>

            </b-col>

        </b-row>

        <b-row>

            <b-col v-for="styleCard in styleCards" :key="'style-card-'+styleCard.style.name" lg="4">

                <card-show-style :subject-style="styleCard.style" :collapsed.sync="styleCard.collapsed"></card-show-style>

            </b-col>

        </b-row>

    </b-container>

</template>

<script>

    import { GROUP_STYLES } from "../styles/groups";
    import RefTagGroup from "./RefTagGroup";
    import TablerCard from "./TablerCard";
    import CardShowStyle from "./CardShowStyle";
    import FormSwitch from "./FormSwitch";

    export default {
        components: {
            FormSwitch,
            CardShowStyle,
            TablerCard,
            RefTagGroup,
        },
        name: "the-page-home",

        data:function() {
            return {
                styleCards: GROUP_STYLES.map(el => {
                    return {
                        style: el,
                        collapsed: true
                    };
                }),
                options: GROUP_STYLES,
                val: [],
            }
        },

        methods:{

        }

    }
</script>

<style scoped>

</style>