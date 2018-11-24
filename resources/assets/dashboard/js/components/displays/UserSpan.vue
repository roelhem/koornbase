<template>
    <span :class="{'d-inline-flex':stacked}">
        <template v-if="user">

            <user-avatar v-if="hasAvatar"
                         :user="user"
                         :size="stacked ? null : 'sm'"
                         class="mr-1"
            />

            <span :class="{'ml-2 d-block leading-none':stacked}">
                <span-person-name v-if="hasPerson"
                                  :person-name="user.person.name"
                />
                <base-field v-else
                            title="Gebruikersnaam"
                            name="name"
                >{{ user.name }}</base-field>

                <span v-if="withEmail || withUsername"
                      class="text-muted"
                      :class="{'d-block small mt-1':stacked}"
                >
                    <base-field v-if="withUsername" class="text-muted-dark" title="Gebruikersnaam" name="name">{{ user.name }}</base-field>
                    <template v-if="withUsername && withEmail">:</template>
                    <template v-if="withEmail">[ <base-field title="E-mail" name="email">{{ user.email }}</base-field> ]</template>
                </span>
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
                id
                name
                email
                person {
                    id
                    name { ...SpanPersonName }
                }
                ...UserAvatar
            }
            ${UserAvatar.fragment}
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
            },

            withUsername:{
                type:Boolean,
                default:false,
            },

            stacked:{
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
            },

            showUsername() {
                return this.withUsername && this.hasPerson;
            }
        }
    }
</script>

<style scoped>

</style>