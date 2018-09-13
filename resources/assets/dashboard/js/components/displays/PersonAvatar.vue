<template>
    <base-avatar v-bind="$attrs"
                 v-on="$listeners"
                 :image="avatar.image"
                 :letters="avatar.letters"
                 default-style="person-default"
    />
</template>

<script>
    import gql from 'graphql-tag';
    import BaseAvatar from "./BaseAvatar";

    export default {
        name: "person-avatar",

        fragment: gql`
            fragment PersonAvatar on Person {
                avatar {
                    ...BaseAvatar
                }
            }
            ${BaseAvatar.fragment}
        `,

        props:{
            person:{
                type:Object,
                required:true,
                default() {
                    return {
                        avatar:{},
                    };
                }
            },
        },

        computed: {
            avatar() {
                if(this.person && this.person.avatar) {
                    return this.person.avatar;
                }
                return {};
            }
        },

        components: {BaseAvatar},
    }
</script>

<style scoped>

</style>