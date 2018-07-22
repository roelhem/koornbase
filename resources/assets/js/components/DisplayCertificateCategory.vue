<template>

    <span v-b-tooltip.hover.html="tooltipMethod">
        <slot>{{ name_short }}</slot>
    </span>

</template>

<script>

    export default {


        props: {
            name_short:String,
            name:String,
            description:String
        },

        methods: {
            tooltipMethod() {

                const name = this.name || '';
                const description = this.description || '';

                return '<h5>'+name+'</h5>'+description;
            },
        },

        name: "display-certificate-category"
    }
</script>

<style scoped>

</style>