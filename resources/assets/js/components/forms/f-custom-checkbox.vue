<template>

    <label class="custom-control custom-checkbox" :class="{'custom-control-inline':inline}">
        <input type="checkbox" class="custom-control-input" :checked="checked" @change="$emit('change', $event.target.checked)" :value="value">
        <span class="custom-control-label"><slot></slot></span>
    </label>

</template>

<script>
    export default {
        name: "f-custom-checkbox",
        model: {
            prop: 'checked',
            event: 'change'
        },
        props: {
            checked: {
                type:Boolean,
                default:false
            },
            inline: {
                type:Boolean,
                default:false
            },
            value: null,
            name: null
        },
    }
</script>

<style scoped>

</style>