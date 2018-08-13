<template>

    <tabler-card title="Tijdstempel filter"
                 status="teal-dark"
                 :icon="{fe:'filter',fa:'filter'}"
                 :collapsed="!activeValue"
    >
        <template slot="options">
            <form-switch type="checkbox" v-model="activeValue" />
        </template>


        <v-date-picker mode="range" v-model="test">
        </v-date-picker>


    </tabler-card>

</template>

<script>
    import TablerCard from "./TablerCard";
    import FormSwitch from "./FormSwitch";

    export default {
        components: {
            FormSwitch,
            TablerCard
        },

        name: "search-filter-timestamp-card",

        data() {
            return {
                test:null
            };
        },

        props: {

            active:{
                type:Boolean,
                default:false,
            },

            value:{
                type:Object,
                default:function() {
                    return {};
                }
            }

        },

        computed: {
            activeValue: {
                get() { return this.active; },
                set(newValue) { this.$emit('update:active', newValue); }
            },
            valueModelled: {
                get() { return this.value; },
                set(newValue) { this.$emit('input', newValue); }
            }
        }
    }
</script>

<style scoped>

</style>