<template>

    <form-layout submit-text="Aanvragen" v-bind="formLayoutProps" v-on="formLayoutListeners">

        <template v-if="isCustomClient">
            <tabler-form-group label="Client">
                <o-auth-client-span :client="client" large />
            </tabler-form-group>
        </template>


        <form-simple-input :id="getFieldId('name')"
                           label="Naam van de Access-Token"
                           placeholder="Laat leeg voor een automatisch gegenereerde naam."
                           v-model="values.name"
        />

        <tabler-form-group label="Scopes">
            <o-auth-scope-checkbox-group stacked v-model="values.scopes" />
        </tabler-form-group>

    </form-layout>

</template>

<script>
    import gql from "graphql-tag";
    import FormLayout from "../../layouts/forms/FormLayout";
    import controlForm from "../../../mixins/controlForm";
    import FormSimpleInput from "../../inputs/FormSimpleInput";
    import OAuthScopeCheckboxGroup from "../../inputs/OAuthScopeCheckboxGroup";
    import TablerFormGroup from "../../layouts/forms/TablerFormGroup";
    import OAuthClientSpan from "../../displays/OAuthClientSpan";

    export default {
        components: {
            OAuthClientSpan,
            TablerFormGroup,
            OAuthScopeCheckboxGroup,
            FormSimpleInput,
            FormLayout
        },

        mixins:[controlForm],

        clientFragment:gql`
            fragment RequestPersonalAccessTokenFormClient on OAuthClient {
                id
                ...OAuthClientSpan
            }
            ${OAuthClientSpan.fragment}
        `,

        name: "request-personal-access-token-form",

        form:{
            title:"Personal Access Token aanvragen",
            values() {
                return {
                    name:null,
                    scopes:[]
                };
            }
        },

        props:{
            client: {
                type:Object,
                default() { return {}; }
            }
        },

        computed:{
            isCustomClient() {
                return !!this.client.id;
            }
        },

        methods:{
            reset() {
                this.values = this.getInitFormValues();
            },

            submit() {
                this.$apollo.mutate({
                    mutation:gql`
                        mutation requestPersonalAccessToken($name:String $clientId:ID $scopes:[OAuthScope]) {
                            request:requestPersonalAccessToken(name:$name clientId:$clientId scopes:$scopes) {
                                accessToken
                            }
                        }
                    `,
                    variables:{
                        name:this.values.name,
                        clientId:this.client.id || null,
                        scopes:this.values.scopes,
                    }
                }).then(data => {
                    console.log(data);
                    alert(data.data.request.accessToken);
                    this.hide();
                }).catch(error => {
                    this.addMessage(error.message);
                });
            }
        }
    }
</script>

<style scoped>

</style>