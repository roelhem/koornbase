<template>

    <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">

        <b-container>

            <b-row class="align-items-center">

                <b-col lg order-lg="first">

                    <b-nav tabs class="border-0 flex-column flex-lg-row">

                        <b-nav-item to="/home" :active="$route.path === '/'">
                            <base-icon icon="home" from="fe" /> Home
                        </b-nav-item>

                        <b-nav-item-dropdown to="/db" extra-menu-classes="dropdown-menu-arrow" :extra-toggle-classes="$route.path.startsWith('/db') ? 'active' : ''">
                            <template slot="button-content"><base-icon icon="database" from="fe" /> Administratie</template>
                            <b-dropdown-item to="/db/persons">Personen</b-dropdown-item>
                            <b-dropdown-item to="/db/groups">Groepen</b-dropdown-item>
                            <b-dropdown-item to="/db/certificates">Certificaten</b-dropdown-item>
                            <b-dropdown-item to="/db/cards">Koornbeurs kaarten</b-dropdown-item>
                        </b-nav-item-dropdown>

                        <b-nav-item-dropdown to="/events" extra-menu-classes="dropdown-menu-arrow" :extra-toggle-classes="$route.path.startsWith('/events') ? 'active' : ''">
                            <template slot="button-content"><base-icon icon="calendar" from="fe" /> Evenementen</template>
                            <b-dropdown-item to="/events/calendar">Kalender</b-dropdown-item>
                            <b-dropdown-item to="/events/tasks">Taken</b-dropdown-item>
                        </b-nav-item-dropdown>

                        <b-nav-item-dropdown to="/oauth"  extra-menu-classes="dropdown-menu-arrow" :extra-toggle-classes="$route.path.startsWith('/oauth') ? 'active' : ''">
                            <template slot="button-content"><base-icon icon="cloud" from="fe" /> OAuth</template>
                            <b-dropdown-item to="/oauth/apps">Apps</b-dropdown-item>
                            <b-dropdown-item to="/oauth/clients">Clients</b-dropdown-item>
                        </b-nav-item-dropdown>

                        <b-nav-item to="/security"><base-icon icon="shield" from="fe" /> Beveiliging</b-nav-item>

                        <b-nav-item to="/users"><base-icon icon="users" from="fe" /> Gebruikers</b-nav-item>

                        <b-nav-item v-for="navItem in navItems"
                                    :key="navItem.id"
                                    :to="navItem.to">

                            <base-icon :icon="navItem.icon" :from="['fe','fa']" />

                            {{ navItem.label }}

                        </b-nav-item>

                    </b-nav>

                </b-col>

            </b-row>

        </b-container>

    </div>

</template>

<script>
    import BaseIcon from "./BaseIcon";

    export default {

        components: {BaseIcon},

        computed: {
            routes: function() {
                return this.$router.options.routes;
            },

            navItems: function() {
                let res = [];
                for(let i=0; i<this.routes.length; i++) {
                    let route = this.routes[i];
                    let navItem = this.routeToNavItem(route);
                    if(navItem) {
                        res.push(navItem);
                    }
                }
                return res;
            }
        },

        methods: {
            routeToNavItem(route) {
                if(!route.meta || !route.meta.headerNavbar) {
                    return false;
                }

                const meta = route.meta;

                return {
                    id:'nav-for' + route.name,
                    to:{name: route.name},
                    label: meta.label || route.name,
                    icon: meta.icon || {}
                };
            }
        },


        name: "the-nav-header"
    }
</script>

<style scoped>

</style>