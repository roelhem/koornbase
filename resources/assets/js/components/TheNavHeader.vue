<template>

    <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">

        <b-container>

            <b-row class="align-items-center">

                <b-col lg order-lg="first">

                    <b-nav tabs class="border-0 flex-column flex-lg-row">

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