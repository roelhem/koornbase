<template>

    <draggable v-model="columns" class="list-group list-group-flush">

        <column-select-entry v-for="column in columns" :key="column.key" :column="column"></column-select-entry>

    </draggable>

</template>

<!--<ul class="list-group list-group-flush">
        <li class="list-group-item p-0" v-for="col in value">
            <label class="custom-switch d-block p-2">
                <input type="checkbox" class="custom-switch-input" v-model="col.visible" @click="trigger">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description">{{ col.name }}</span>
            </label>
        </li>
    </ul>-->

<script>
    import draggable from 'vuedraggable'
    import ColumnSelectEntry from "./column-select-entry";

    export default {
        name: "column-select",
        props: {
            value:Array
        },
        data: function() {
            return {
                columns: this.value
            }
        },
        components: {
            ColumnSelectEntry,
            draggable
        },
        watch: {
            columns: function(val) {
                this.$emit('input', val);
            }
        }
    }
</script>

<style scoped>

</style>