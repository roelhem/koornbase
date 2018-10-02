<template>
    <a :href="link" class="btn btn-icon btn-subtile btn-subtile-purple" :class="{'btn-sm':size === 'sm'}">
        <base-icon icon="phone-outgoing" from="fe" />
    </a>
</template>

<script>
    import gql from "graphql-tag";
    import BaseIcon from "../../displays/BaseIcon";

    export default {
        components: {BaseIcon},
        name: "phone-call-button",

        fragment:gql`
            fragment PhoneCallButton on PhoneNumber {
                link
            }
        `,

        props: {
            phoneNumber:{
                type:Object,
                required:true
            },

            size: {
                type:String,
                default:'sm'
            }
        },

        computed: {
            link() {
                return this.phoneNumber.link;
            }
        }
    }
</script>

<style scoped>

</style>