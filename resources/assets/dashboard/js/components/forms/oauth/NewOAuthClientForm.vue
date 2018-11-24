<template>

    <form-layout v-bind="formLayoutProps" v-on="formLayoutListeners" :title="title">

        <form-simple-input :id="getFieldId('name')"
                           label="Client naam"
                           placeholder="Naam voor de nieuwe OAuth-client."
                           v-model.trim="$v.values.name.$model"
                           :validation="$v.values.name"
                           required
        />

        <tabler-form-group label="Eigenaar/Beheerder"
                           :label-for="getFieldId('user')"
                           :validation="$v.values.user"
        >
            <user-select :id="getFieldId('user')"
                         v-model="$v.values.user.$model"
            />
        </tabler-form-group>

        <template v-if="type.hasRedirect">
            <form-simple-input :id="getFieldId('redirect')"
                               label="Redirect URL"
                               :required="type.requireRedirect"
                               placeholder="De URL waarnaar wordt verwezen bij een OAuth-redirect."
                               v-model.trim="$v.values.redirect.$model"
                               :validation="$v.values.redirect"
            />
        </template>

    </form-layout>

</template>

<script>
    import gql from "graphql-tag";
    import { AUTH_CODE_CLIENT_TYPE, AUTH_CODE, PASSWORD, PERSONAL, CREDENTIALS } from "../../../constants/oauth-client-types";
    import { validationMixin } from 'vuelidate';
    import { required, url, maxLength } from 'vuelidate/lib/validators';
    import controlForm from "../../../mixins/controlForm";
    import FormLayout from "../../layouts/forms/FormLayout";
    import FormSimpleInput from "../../inputs/FormSimpleInput";
    import TablerFormGroup from "../../layouts/forms/TablerFormGroup";
    import UserSelect from "../../inputs/select/UserSelect";


    const CreateOAuthClientResult = gql`
        fragment CreateOAuthClientResult on OAuthClient {
            id
        }
    `;

    const createOAuthAuthCodeClient = gql`
        mutation createOAuthAuthCodeClient($name:String! $redirect:String! $userId:ID) {
            createOAuthClient:createOAuthAuthCodeClient(name:$name redirect:$redirect userId:$userId) {
                ...CreateOAuthClientResult
            }
        }
        ${CreateOAuthClientResult}
    `;

    const createOAuthPasswordClient = gql`
        mutation createOAuthPasswordClient($name:String! $redirect:String $userId:ID) {
            createOAuthClient:createOAuthPasswordClient(name:$name redirect:$redirect userId:$userId) {
                ...CreateOAuthClientResult
            }
        }
        ${CreateOAuthClientResult}
    `;

    const createOAuthPersonalClient = gql`
        mutation createOAuthPersonalClient($name:String! $userId:ID) {
            createOAuthClient:createOAuthPersonalClient(name:$name userId:$userId) {
                ...CreateOAuthClientResult
            }
        }
        ${CreateOAuthClientResult}
    `;

    const createOAuthCredentialsClient = gql`
        mutation createOAuthCredentialsClient($name:String! $userId:ID) {
            createOAuthClient:createOAuthCredentialsClient(name:$name userId:$userId) {
                ...CreateOAuthClientResult
            }
        }
        ${CreateOAuthClientResult}
    `;

    export default {
        components: {
            UserSelect,
            TablerFormGroup,
            FormSimpleInput,
            FormLayout
        },

        props: {
            type: {
                type:Object,
                default() {
                    return AUTH_CODE_CLIENT_TYPE;
                }
            }
        },

        form:{
            actionType:"create",
            values() {
                return {
                    name:null,
                    redirect:null,
                    user:null
                };
            }
        },

        name: "new-o-auth-client-form",

        mixins:[validationMixin, controlForm],

        validations() {
            return {
                values: {
                    name: {
                        required,
                        maxLength:maxLength(255)
                    },
                    redirect: this.type.requireRedirect ? {
                        url, required
                    } : this.type.hasRedirect ? {
                        url
                    } : {},
                    user: { },
                }
            };
        },

        computed:{
            title() {
                return "Nieuwe " + this.type.name;
            },

            userId() {
                if(this.values.user) {
                    return this.values.user.id;
                }
                return null;
            },

            mutationQuery() {
                switch(this.type.key) {
                    case AUTH_CODE: return createOAuthAuthCodeClient;
                    case PASSWORD: return createOAuthPasswordClient;
                    case PERSONAL: return createOAuthPersonalClient;
                    case CREDENTIALS: return createOAuthCredentialsClient;
                }
            },

            mutationVariables() {
                const name = this.values.name;
                const redirect = this.values.redirect;
                const userId = this.userId;

                switch(this.type.key) {
                    case AUTH_CODE: return {name,redirect,userId};
                    case PASSWORD: return {name,redirect,userId};
                    case PERSONAL: return {name,userId};
                    case CREDENTIALS: return {name,userId};
                }
            }
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
                        mutation: this.mutationQuery,
                        variables: this.mutationVariables
                    }).then(data => {
                        console.log(data);
                        this.hide();
                        this.$router.push({
                            name: 'oauth.clients.view',
                            params:{id: data.data.createOAuthClient.id}
                        });
                    }).catch(error => {
                        this.addMessage(error.message);
                    });
                }

            }
        },


    }
</script>

<style scoped>

</style>