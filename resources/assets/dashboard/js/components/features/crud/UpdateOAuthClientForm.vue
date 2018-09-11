<template>

    <form-layout v-bind="formLayoutProps" v-on="formLayoutListeners">

        <form-simple-input :id="getFieldId('name')"
                           label="Naam"
                           placeholder="Nieuwe naam voor de client."
                           required
                           v-model.trim="$v.values.name.$model"
                           :validation="$v.values.name"
        />

        <form-simple-input v-if="showRedirect"
                           :id="getFieldId('redirect')"
                           label="Redirect"
                           placeholder="De URL waarnaar wordt verwezen bij een OAuth-redirect."
                           :required="requireRedirect"
                           v-model.trim="$v.values.redirect.$model"
                           :validation="$v.values.redirect"
        />
    </form-layout>

</template>

<script>
    import controlForm from "../../../mixins/controlForm";
    import { getOAuthClientForUpdateForm } from "../../../apis/graphql/queries/oauth.graphql";
    import { updateOAuthClient } from "../../../apis/graphql/mutations/oauth.graphql";
    import { required, url, maxLength } from 'vuelidate/lib/validators';
    import { validationMixin } from 'vuelidate';
    import FormLayout from "../../layouts/forms/FormLayout";
    import FormSimpleInput from "../../inputs/FormSimpleInput";

    const CREDENTIALS = "CREDENTIALS";
    const AUTH_CODE = "AUTH_CODE";
    const PERSONAL = "PERSONAL";
    const PASSWORD = "PASSWORD";

    export default {
        name: "update-o-auth-client-form",

        mixins: [controlForm, validationMixin],

        // APOLLO SETTINGS
        apollo: {
            client: {
                query: getOAuthClientForUpdateForm,
                variables() { return { id:this.clientId }; },
                result({ data }) {
                    if(this.values.name === null) { this.values.name = data.client.name; }
                    if(this.values.redirect === null && data.client.type !== CREDENTIALS) {
                        this.values.redirect = data.client.redirect;
                    }
                }
            }
        },


        // PROPERTIES
        props: {

            clientId:{
                type:[String,Number],
                required:true,
            },

            current:{
                type:Object,
                default() { return {}; }
            }
        },


        // DATA VARIABLES
        data() {
            return {
                client: {
                    id: this.clientId,
                    type: this.current.type || null,
                    name: this.current.name || null,
                    redirect: this.current.redirect || null,
                }
            };
        },

        // COMPUTED VARIABLES
        computed: {
            showRedirect() { return this.client.type !== CREDENTIALS; },
            requireRedirect() { return this.client.type === AUTH_CODE; },

            variables() {
                return {
                    id:this.clientId,
                    name:this.values.name,
                    redirect:this.values.redirect
                }
            }
        },

        // METHODS
        methods: {
            reset() {
                this.values = this.getInitFormValues();
                this.$v.values.$reset();
            },
            submit() {

                this.$v.values.$touch();

                if(this.$v.values.$invalid) {
                    this.addMessage("Er zijn nog velden met ongeldige waarden. Pas de velden aan en probeer het opnieuw.");
                } else {
                    this.$apollo.mutate({
                        mutation:updateOAuthClient,
                        variables:this.variables,
                    }).then(data => {
                        console.log(data);
                        this.hide();
                    }).catch(error => {
                        this.addMessage(error.message);
                    });
                }
            },
        },

        // FORM OPTIONS
        form: {
            title() {
                const name = this.client.name;
                if(name) {
                    return `'${name}' Bewerken`;
                } else {
                    const id = this.clientId;
                    return `OAuth Client ${id} Bewerken`;
                }

            },
            actionType:'update',
            values() { return { name: null, redirect: null }; }
        },

        // VALIDATION OPTIONS
        validations() {
            return {
                values: {
                    name: { required, maxLength:maxLength(255) },
                    redirect: this.client.type === AUTH_CODE ? { url, required} : { url }
                }
            }
        },

        // COMPONENTS
        components: {
            FormSimpleInput,
            FormLayout
        },
    }
</script>

<style scoped>

</style>