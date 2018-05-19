<template>

    <draggable v-model="columns" class="list-group list-group-flush" :options="{ghostClass:'bg-gray-lighter'}">

        <column-select-entry v-for="column in columns" :key="column.key" :column="column"></column-select-entry>

    </draggable>

</template>

<script>
    import draggable from 'vuedraggable'
    import ColumnSelectEntry from "./column-select-entry";

    export default {
        name: "column-select",
        props: {
            value:Array
        },
        computed: {
            columns: {
                get: function() {
                    return this.value;
                },
                set: function(newValue) {
                    this.$emit('input', newValue);
                },
            }
        },


        components: {
            ColumnSelectEntry,
            draggable
        },
    }
</script>

<style scoped>

</style>