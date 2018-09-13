<template>
    <base-avatar v-bind="$attrs"
                 v-on="$listeners"
                 :image="avatar.image"
                 :letters="avatar.letters"
                 :default-style="defaultStyle"
    />
</template>

<script>
    import gql from "graphql-tag";
    import BaseAvatar from "./BaseAvatar";

    export default {
        components: {
            BaseAvatar
        },
        name: "user-avatar",

        fragment:gql`
            fragment UserAvatar on User {
                avatar {
                    ...BaseAvatar
                }
            }
            ${BaseAvatar.fragment}
        `,

        props: {
            user: {
                type:Object,
                required:true,
                default() {
                    return {
                        avatar:{},
                    }
                }
            }
        },

        computed: {
            avatar() {
                if(this.user && this.user.avatar) {
                    return this.user.avatar;
                }
                return {};
            },

            hasPerson() {
                return this.user && this.user.person;
            },

            defaultStyle() {
                return this.hasPerson ? 'person-default' : 'user-default';
            }
        }

    }
</script>

<style scoped>

</style>