<template>
    <div class="dimmer" :class="{active:active}">
        <div class="loader"></div>
        <div class="dimmer-content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "tabler-dimmer",

        props: {
            active:{
                type:Boolean,
                default:false,
            }
        }
    }
</script>

<style scoped>

</style>