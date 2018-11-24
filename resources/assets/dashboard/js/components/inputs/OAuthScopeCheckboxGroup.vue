<template>
    <b-form-checkbox-group v-bind="$attrs" v-on="$listeners">
        <b-form-checkbox v-for="scope in scopes"
                         :key="scope.name"
                         :value="scope.name"
                         v-b-tooltip.hover.left="scope.description"
        >
            <strong>{{ scope.name }}</strong>
        </b-form-checkbox>
    </b-form-checkbox-group>
</template>

<script>
    import gql from "graphql-tag";

    export default {
        name: "o-auth-scope-checkbox-group",

        apollo:{
            scopeType:{
                query:gql`
                    query getOAuthScopes {
                        scopeType:__type(name:"OAuthScope") {
                            enumValues {
                                name
                                description
                            }
                        }
                    }
                `
            }
        },

        model:{
            prop:"checked",
            event:"change"
        },

        data() {
            return {
                scopeType:{
                    enumValues:[]
                }
            };
        },

        computed: {
            scopes() {
                return this.scopeType.enumValues;
            }
        }
    }
</script>

<style scoped>

</style>