<template>

    <form-layout ref="formLayout"
                 :modal="modal"
                 title="OAuth-client intrekken"
                 form-id="revoke-o-auth-client-form"
                 :messages.sync="messages"
                 action-type="delete"
    >
        <p>Weet je zeker dat je deze OAuth Client wilt intrekken?</p>
    </form-layout>

</template>

<script>
    import FormLayout from "../../layouts/forms/FormLayout";

    export default {
        components: {FormLayout},
        name: "revoke-o-auth-client-form",

        props: {
            modal:Boolean,
        }
    }
</script>

<style scoped>

</style>