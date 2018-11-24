<template>
    <span>
        <base-field title="OAuth-client Type" name="type">
            <o-auth-client-type-tag :type="client.type"
                                    :large="large"
            />
        </base-field>
        <span class="mx-1 text-muted">:</span>
        <base-field title="Naam" name="name" :value="client.name" />
    </span>
</template>

<script>
    import gql from "graphql-tag";
    import BaseField from "./BaseField";
    import OAuthClientTypeTag from "./OAuthClientTypeTag";

    export default {
        components: {
            OAuthClientTypeTag,
            BaseField
        },

        name: "o-auth-client-span",

        fragment:gql`
            fragment OAuthClientSpan on OAuthClient {
                type
                name
            }
        `,

        props:{
            client:{
                type:Object,
                required:true,
            },

            large:{
                type:Boolean,
                default:false
            }
        }
    }
</script>

<style scoped>

</style>