<template>

    <tabler-card no-body title="Groepen filter" icon="filter" status="teal-dark" :collapsed="!activeValue">
        <template slot="options">
            <form-switch v-model="activeValue" />
        </template>

        <group-select multiple no-border size="sm" v-model="valueModelled" />

    </tabler-card>

</template>

<script>
    import TablerCard from "../../layouts/cards/TablerCard";
    import FormSwitch from "../../inputs/FormSwitch";
    import GroupSelect from "../../inputs/select/GroupSelect";

    export default {
        components: {
            GroupSelect,
            FormSwitch,
            TablerCard},
        name: "search-filter-group-card",

        props: {

            active: {
                type:Boolean,
                default:false,
            },

            value: {
                type:Array,
                default:[],
            },

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