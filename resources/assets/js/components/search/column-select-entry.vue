<template>

    <div class="list-group-item p-0 column-select-entry">
        <div class="d-flex">
            <label class="custom-switch d-block p-2 flex-grow-1">
                <input type="checkbox" class="custom-switch-input" :checked="column.visible" @change="onChange($event.target.checked)">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description">{{ column.name }}</span>
            </label>
            <span class="column-select-entry-handle text-muted p-2">
                <i class="fa fa-bars"></i>
            </span>
        </div>
    </div>

</template>

<script>
    export default {
        name: "column-select-entry",
        props: {
            column:Object
        },
        model: {
            prop:'column',
            event:'change',
        },
        methods: {
            onChange(visible) {
                let result = this.column;
                result.visible = visible;
                this.$emit('change', result);
            }
        }

    }
</script>

<style scoped>

</style>