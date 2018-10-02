<template>

    <div class="dropdown">

        <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
            <user-avatar :user="me" />
            <span class="ml-2 d-none d-lg-block">
                <span class="text-default">
                    <span-person-name v-if="me.person" :person-name="me.person.name" />
                    <span v-else>{{ me.name }}</span>
                </span>
                <span class="text-muted d-block mt-1 small">{{ me.email }}</span>
            </span>
        </a>

        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

            <b-dropdown-item :to="{ name:'me.overview' }">
                <base-icon class="dropdown-icon" icon="user" from="fe" />
                Mijn Account
            </b-dropdown-item>

            <b-dropdown-item href="#">
                <base-icon class="dropdown-icon" icon="settings" from="fe" />
                Instellingen
            </b-dropdown-item>

            <b-dropdown-divider></b-dropdown-divider>

            <b-dropdown-item href="#">
                <base-icon class="dropdown-icon" icon="lock" from="fe" />
                Vergrendelen
            </b-dropdown-item>

            <b-dropdown-item @click="$store.dispatch('logoutCurrentUser')">
                <base-icon class="dropdown-icon" icon="log-out" from="fe" />
                Uitloggen
            </b-dropdown-item>

        </div>

    </div>

</template>

<script>
    import gql from "graphql-tag";
    import BaseIcon from "../../displays/BaseIcon";
    import SpanPersonName from "../../displays/spans/SpanPersonName";
    import UserAvatar from "../../displays/UserAvatar";

    export default {
        components: {
            UserAvatar,
            SpanPersonName,
            BaseIcon
        },
        name: "user-menu",

        apollo: {
            me: gql`
                query GetUserDropdown {
                    me {
                        id
                        name
                        email
                        person {
                            id
                            name { ...SpanPersonName }
                        }
                        ...UserAvatar
                    }
                }
                ${SpanPersonName.fragment}
                ${UserAvatar.fragment}
            `,
        },

        data() {
            return {
                me:{}
            }
        },

        props: {
            id: Number,
            name: String,
            email: String,
            avatar: Object,
            name_display: String
        }
    }
</script>

<style scoped>

</style>