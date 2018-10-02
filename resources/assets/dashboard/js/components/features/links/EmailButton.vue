<template>

    <a :href="link" class="btn btn-icon btn-subtile btn-subtile-purple" :class="{'btn-sm':size === 'sm'}">
        <base-icon icon="mail" from="fe" />
    </a>

</template>

<script>
    import gql from "graphql-tag";
    import BaseIcon from "../../displays/BaseIcon";

    export default {
        components: {BaseIcon},
        name: "email-button",

        fragment: gql`
            fragment EmailButton on EmailAddress {
                email
                link
            }
        `,

        props: {

            emailAddress: {
                type:Object,
                required:true,
            },

            size: {
                type:String,
                default:'sm'
            }
        },

        computed: {
            link() {
                if(this.emailAddress.link) {
                    return this.emailAddress.link;
                } else {
                    return "mailto:"+this.emailAddress.email;
                }
            }
        }
    }
</script>

<style scoped>

</style>