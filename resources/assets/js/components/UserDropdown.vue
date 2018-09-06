<template>

    <div class="dropdown">

        <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
            <base-avatar v-bind="currentUser.avatar" color="blue" />
            <span class="ml-2 d-none d-lg-block">
                <span class="text-default">{{ currentUser.name_display }}</span>
                <span class="text-muted d-block mt-1 small">{{ currentUser.email }}</span>
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

            <b-dropdown-item @click="$store.dispatch('logoutCurrentUser')">
                <base-icon class="dropdown-icon" icon="log-out" from="fe" />
                Uitloggen
            </b-dropdown-item>

        </div>

    </div>

</template>

<script>
    import { currentUser } from "../graphql/dashboard.graphql";
    import BaseAvatar from "./BaseAvatar";
    import BaseIcon from "./BaseIcon";

    export default {
        components: {
            BaseIcon,
            BaseAvatar
        },
        name: "user-menu",

        apollo: {
            currentUser:currentUser
        },

        data() {
            return {
                currentUser:{}
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