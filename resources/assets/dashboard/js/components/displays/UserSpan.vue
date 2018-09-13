<template>
    <span>
        <template v-if="user">
            <user-avatar v-if="hasAvatar"
                         :user="user"
                         size="sm"
                         class="mr-1"
            />

            <span-person-name v-if="hasPerson" :person="user.person" />
            <base-field v-else title="Gebruikersnaam" name="name">{{ user.name }}</base-field>

            <span v-if="withEmail" class="text-muted">
                [ <base-field title="E-mail" name="email">{{ user.email }}</base-field> ]
            </span>

        </template>
    </span>
</template>

<script>
    import gql from "graphql-tag";
    import SpanPersonName from "./spans/SpanPersonName";
    import BaseField from "./BaseField";
    import UserAvatar from "./UserAvatar";

    export default {
        components: {
            UserAvatar,
            BaseField,
            SpanPersonName
        },
        name: "user-span",

        fragment:gql`
            fragment UserSpan on User {
                name
                email
                person {
                    id
                    ...SpanPersonName
                }
                avatar {
                    image
                    letters
                }
            }
            ${SpanPersonName.fragment}
        `,

        props:{
            user:{
                type:Object,
                required:true
            },

            withEmail:{
                type:Boolean,
                default:false,
            }
        },

        computed:{
            hasAvatar() {
                return !!this.user.avatar;
            },

            hasPerson() {
                return !!this.user.person;
            }
        }
    }
</script>

<style scoped>

</style>