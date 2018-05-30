<template>

    <tabler-card no-body title="Groepen filter" icon="filter" status="teal-dark" :collapsed="!activeValue">
        <template slot="options">
            <form-switch v-model="activeValue" />
        </template>

        <form-model-select-multi multiple no-border model="group" v-model="valueModelled"></form-model-select-multi>

    </tabler-card>

</template>

<script>
    import TablerCard from "./TablerCard";
    import FormModelSelectMulti from "./FormModelSelectMulti";
    import FormModelSelectTree from "./FormModelSelectTree";
    import FormSwitch from "./FormSwitch";

    export default {
        components: {
            FormSwitch,
            FormModelSelectMulti,
            FormModelSelectTree,
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