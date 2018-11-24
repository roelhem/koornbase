<template>

    <div>
        <b-dropdown :variant="variant" :text="text" @click="openModal(defaultType)" split v-bind="$attrs" v-on="$listeners">
            <b-dropdown-header>Wat voor client?</b-dropdown-header>
            <o-auth-client-type-dropdown-item v-for="type in types"
                                              :key="`dropdown-item-${type.key}`"
                                              :type="type.key"
                                              @click="openModal(type)"
            />
        </b-dropdown>

        <new-o-auth-client-form ref="modal" :type="modalType" modal />
    </div>

</template>

<script>
    import OAUTH_CLIENT_TYPES from "../../../constants/oauth-client-types";
    import OAuthClientTypeTag from "../../displays/OAuthClientTypeTag";
    import OAuthClientTypeDropdownItem from "../../displays/OAuthClientTypeDropdownItem";
    import NewOAuthClientForm from "../../forms/oauth/NewOAuthClientForm";

    export default {
        components: {
            NewOAuthClientForm,
            OAuthClientTypeDropdownItem,
            OAuthClientTypeTag
        },
        name: "new-o-auth-client-button",

        data() {
            return {
                modalType:this.defaultType
            }
        },

        props: {
            variant:{
                type:String,
                default:"success"
            },

            text:{
                type:String,
                default:"Nieuwe Client"
            }
        },

        computed: {
            types() {
                return OAUTH_CLIENT_TYPES;
            },

            defaultType() {
                return this.types.find(clientType => clientType.default);
            }
        },

        methods: {
            openModal(type) {
                this.modalType = type;
                this.$refs.modal.show();
            }
        }
    }
</script>

<style scoped>

</style>