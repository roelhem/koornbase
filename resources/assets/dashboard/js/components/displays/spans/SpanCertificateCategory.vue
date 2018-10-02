<template>

    <span v-b-tooltip.hover.html="tooltipMethod">
        <slot>
            <base-field title="Korte naam" name="shortName">{{ category.shortName }}</base-field>
        </slot>
    </span>

</template>

<script>
    import gql from "graphql-tag";

    import BaseField from "../BaseField";

    export default {
        components: {BaseField},
        name: "span-certificate-category",

        fragment: gql`
            fragment SpanCertificateCategory on CertificateCategory {
                name
                shortName
                description
            }
        `,

        props: {
            category:{
                type:Object,
                default:function() {
                    return {
                        shortName:null,
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