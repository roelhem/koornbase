<template>

    <div class="input-icon">
        <div v-if="prepend" class="input-icon-addon">
            <base-icon :icon="prepend" :from="from"></base-icon>
        </div>
        <slot></slot>
        <div v-if="append" class="input-icon-addon">
            <base-icon :icon="append" :from="from"></base-icon>
        </div>
    </div>

</template>

<script>
    import BaseIcon from "../../displays/BaseIcon";

    export default {
        components: {BaseIcon},
        name: "tabler-input-icon",

        props: {
            prepend:[String,Object],
            append:[String,Object],
            from:{
                type: [String,Array],
                default:function() { return ['fe','fa']; },
            },
        }
    }
</script>

<style scoped>

</style>