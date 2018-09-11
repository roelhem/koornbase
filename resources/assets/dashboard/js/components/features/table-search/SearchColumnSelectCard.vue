<template>

    <tabler-card title="Kolommen" collapsible collapsible-with-header no-body
                 :collapsed="collapsed" v-on:update:collapsed="val => $emit('update:collapsed', val)">


        <draggable v-model="cols"
                   class="list-group list-group-flush"
                   :options="{ghostClass:'bg-gray-lighter'}"
        >

            <b-list-group-item v-for="column in columns"
                               :key="`col-${column.key}-list-item`"
                               class="p-2"
                               tag="label"
            >
                <form-switch v-model="column.visible">
                    {{ column.name }}
                </form-switch>
            </b-list-group-item>

        </draggable>

    </tabler-card>

</template>

<script>
    import TablerCard from "../../layouts/cards/TablerCard";
    import FormSwitch from "../../inputs/FormSwitch";
    import draggable from 'vuedraggable';

    export default {
        components: {
            FormSwitch,
            TablerCard,
            draggable,
        },
        name: "search-column-select-card",

        model: {
            prop:'columns',
            event:'change',
        },

        props: {

            collapsed:{
                type:Boolean,
                default:false,
            },

            columns:{
                type:Array,
                default:[]
            }

        },

        computed: {
            cols: {
                get() { return this.columns; },
                set(newValue) { this.$emit('change', newValue); },
            }
        }
    }
</script>

<style scoped>

</style>