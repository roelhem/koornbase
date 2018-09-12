<template>

    <span v-b-tooltip.hover.html="tooltipMethod">
        <slot>
            <base-field title="Korte naam" name="name_short">{{ category.name_short }}</base-field>
        </slot>
    </span>

</template>

<script>
    import BaseField from "../BaseField";

    export default {
        components: {BaseField},
        name: "span-certificate-category",

        props: {
            category:{
                type:Object,
                default:function() {
                    return {
                        name_short:null,
                        name:null,
                        description:null
                    }
                }
            }
        },

        methods: {
            tooltipMethod() {
                const name = this.category.name || '';
                const description = this.category.description || '';

                return '<h5>'+name+'</h5>'+description;
            }
        }
    }
</script>

<style scoped>

</style>