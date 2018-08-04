<template>

    <form-layout v-bind="formLayoutProps"
                 v-on="formLayoutListeners"
    >

        <form-simple-input :id="getFieldId('name')"
                           label="Naam"
                           placeholder="Naam voor de nieuwe OAuth-client."
                           required
                           v-model.trim="$v.values.name.$model"
                           :validation="$v.values.name"
        />

        <tabler-form-group id="create-o-auth-client-form_type_fieldset"
                      label="Type">
            <b-form-radio-group id="create-o-auth-client-form_type" v-model="values.type" stacked>
            <b-form-radio value="AUTH_CODE">
                <strong>Authorization Code Client</strong><br />
                <span class="text-muted-dark">Een client die tokens kan aanvragen met authorizatie-codes. De client
                    krijgt deze authorizatie-code via de redirect url, nadat een gebruiker de client toestemming
                    heeft gegeven. De authorizatie gaat altijd via de KoornBase website.</span>
            </b-form-radio>
            <b-form-radio value="PERSONAL">
                <strong>Personal Access Client</strong><br />
                <span class="text-muted-dark">Een client die persoonlijke tokens kan genereren (via de KoornBase
                    website).</span>
            </b-form-radio>
            <b-form-radio value="PASSWORD">
                <strong>Password Client</strong><br />
                <span class="text-muted-dark">Een client die direct tokens kan aanvragen met een e-mail adres en
                    wachtwoord van de gebruiker. (Alleen voor vertrouwde applicaties!)</span>
            </b-form-radio>
            <b-form-radio value="CREDENTIALS">
                <strong>Credentials Client</strong><br />
                <span class="text-muted-dark">Een client voor <em>machine-to-machine</em> toepassingen.
                    Kan tokens genereren zonder een gebruiker.</span>
            </b-form-radio>
        </b-form-radio-group>
        </tabler-form-group>

        <form-simple-input :id="getFieldId('redirect')"
                           label="Redirect URL"
                           :placeholder="redirectPlaceholder"
                           :required="requireRedirect"
                           :disabled="disableRedirect"
                           v-model.trim="$v.values.redirect.$model"
                           :validation="$v.values.redirect"
        />

    </form-layout>

</template>

<script>
    import TablerFormGroup from "../TablerFormGroup";
    import { createOAuthClient } from "../../mutations/oauth.graphql";
    import controlForm from "../../mixins/controlForm";
    import { required, url, maxLength } from 'vuelidate/lib/validators';
    import { validationMixin } from 'vuelidate';
    import FormLayout from "./FormLayout";
    import FormSimpleInput from "./FormSimpleInput";


    const CREDENTIALS = "CREDENTIALS";
    const AUTH_CODE = "AUTH_CODE";
    const PERSONAL = "PERSONAL";
    const PASSWORD = "PASSWORD";


    export default {
        components: {
            FormSimpleInput,
            FormLayout,
            TablerFormGroup
        },
        mixins: [validationMixin, controlForm],
        name: "create-o-auth-client-form",

        form: {
            title:"Nieuwe OAuth-Client ",
            actionType:"create",
            values() {
                return {
                    name:null,
                    type:AUTH_CODE,
                    redirect:null,
                }
            }
        },

        validations() {
            return {
                values: {
                    name: {
                        required,
                        maxLength:maxLength(255)
                    },
                    redirect: this.values.type === AUTH_CODE ? { url, required} : { url }
                }
            };
        },

        computed: {

            disableRedirect: function() {
                return this.values.type === CREDENTIALS;
            },

            requireRedirect: function() {
                return this.values.type === AUTH_CODE;
            },

            redirectPlaceholder: function() {
                if(this.values.type === AUTH_CODE) {
                    return "De URL waarnaar wordt verwezen bij een OAuth-redirect.";
                } else if(this.values.type === CREDENTIALS) {
                    return "Een Credentials Client heeft geen redirect-URL.";
                } else {
                    return "http://localhost (Alleen voor speciale gevallen)";
                }
            },
        },

        methods: {

            reset() {
                this.values = this.getInitFormValues();

                this.$v.values.$reset();
            },


            submit() {

                this.$v.values.$touch();

                if(this.$v.values.$invalid) {
                    this.addMessage("Sommige velden hebben ongeldige waarden. Pas deze waarden aan en probeer het opnieuw.");
                } else {
                    this.$apollo.mutate({
                        mutation: createOAuthClient,
                        variables: this.values,
                    }).then(data => {
                        console.log(data);
                        this.hide();
                    }).catch(error => {
                        this.addMessage(error.message);
                    });
                }
            },
        }
    }
</script>

<style scoped>

</style>