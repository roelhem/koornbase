<template>

    <tabler-card title="Lidstatus filter"
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
                         color="teal">
                <span class="status-icon" :class="option | membershipStatusColor "></span>
                {{ option | membershipStatusName }}
            </form-switch>
        </div>

    </tabler-card>

</template>

<script>
    import TablerCard from "./TablerCard";
    import FormSwitch from "./FormSwitch";

    import displayFilters from '../filters/display';

    export default {
        components: {
            FormSwitch,
            TablerCard
        },
        filters: displayFilters,
        name: "search-filter-membership-status-card",

        data: function() {
            return {
                options: [0,1,2,3]
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