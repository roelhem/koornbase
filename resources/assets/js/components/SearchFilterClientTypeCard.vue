<template>

    <tabler-card title="Client-type filter"
                 status="teal-dark"
                 :icon="{fe:'filter',fa:'filter'}"
                 :collapsed="!activeValue">
        <template slot="options">
            <form-switch type="checkbox" v-model="activeValue" />
        </template>

        <div class="custom-switches-stacked">
            <form-switch v-for="option in options"
                         :key="`status-${option}`"
                         :value="option"
                         v-model="valueModelled"
                         color="teal"
                         v-b-tooltip.hover.right.d500
                         :title="option | oAuthClientTypeShortDescription">
                    <span class="status-icon" :class="option | oAuthClientTypeBgColor"></span>
                    {{ option | oAuthClientTypeLargeLabel }}

            </form-switch>
        </div>

    </tabler-card>

</template>

<script>
    import TablerCard from "./TablerCard";
    import FormSwitch from "./FormSwitch";
    import DisplayOAuthClientType from "./displays/DisplayOAuthClientType";
    import displayFilters from '../filters/display';

    export default {
        components: {
            DisplayOAuthClientType,
            FormSwitch,
            TablerCard
        },
        name: "search-filter-client-type-card",

        filters:displayFilters,

        data: function() {
            return {
                options: ["PERSONAL","PASSWORD","CREDENTIALS","AUTH_CODE"]
            };
        },

        props: {

            active:{
                type:Boolean,
                default:false,
            },

            value:{
                type:Array,
                default:function() {
                    return [];
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