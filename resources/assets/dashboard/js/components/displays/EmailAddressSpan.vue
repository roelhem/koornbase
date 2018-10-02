<template>

    <span>
        <base-field v-if="withName" class="text-muted-dark small" title="Naam" name="name">{{ emailAddress.name }}</base-field>
        <span class="text-muted">&lt;</span>
        <base-field title="E-mailadres" name="email">{{ emailAddress.email }}</base-field>
        <span class="text-muted">&gt;</span>
        <email-button v-if="withButton" :email-address="emailAddress" />
    </span>

</template>

<script>
    import gql from "graphql-tag";
    import BaseField from "./BaseField";
    import EmailButton from "../features/links/EmailButton";

    export default {
        components: {
            EmailButton,
            BaseField},

        fragment: gql`
            fragment EmailAddressSpan on EmailAddress {
                email
                name
                ...EmailButton
            }
            ${EmailButton.fragment}
        `,

        props:{
            emailAddress:{
                type:Object,
                default() {
                    return {
                        email:null,
                        name:null,
                    };
                }
            },

            withName:{
                type:Boolean,
                default:false,
            },

            withButton:{
                type:Boolean,
                default:false
            }
        },

        name: "email-address-span",
    }
</script>

<style scoped>

</style>