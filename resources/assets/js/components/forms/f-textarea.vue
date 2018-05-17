<template>

    <div class="form-group">
        <label class="form-label" v-if="label">{{ label }}</label>
        <textarea class="form-control" :name="name" v-on:input="$emit('input', $event.target.input)" v-text="value"></textarea>
    </div>

</template>

<script>
    export default {
        name: "f-simple-input",
        props: [
            "label",
            "name",
            "value"
        ]
    }
</script>

<style scoped>

</style>