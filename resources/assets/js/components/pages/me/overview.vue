<template>

    <b-container>

        <tabler-page-header title="Mijn Gegevens" />

        <b-row>


            <b-col lg="4">

                <b-card>
                    <tabler-dimmer :active="$apollo.queries.me.loading">
                    <b-media>
                        <base-avatar slot="aside" size="xxl" class="mr-5" color="blue" v-bind="me.avatar" />
                        <h4 class="m-0">{{ me.name_display }}</h4>
                        <p class="text-muted mb-0">
                            {{me.email}}
                            <small class="text-muted-dark">(<em>{{ me.name }}</em>)</small>
                        </p>
                    </b-media>
                    </tabler-dimmer>
                </b-card>

                <b-list-group>
                    <b-list-group-item :to="{ name:'me.overview.personal' }">Mijn Persoonsgegevens</b-list-group-item>
                    <b-list-group-item :to="{ name:'me.overview.koornbeurs' }">Mijn Koornbeurs</b-list-group-item>
                </b-list-group>

            </b-col>


            <b-col lg="8">
                <router-view />

            </b-col>


        </b-row>

    </b-container>

</template>

<script>
    import DataDisplay from "../../displays/data-display";
    import BaseAvatar from "../../BaseAvatar";
    import PageMePersonalData from "./overview.personal";
    import gql from 'graphql-tag';
    import TablerDimmer from "../../TablerDimmer";
    import TablerPageHeader from "../../TablerPageHeader";

    export default {

        components: {
            TablerPageHeader,
            TablerDimmer,
            PageMePersonalData,
            BaseAvatar,
            DataDisplay
        },

        data: function() {
            return {
                me: {
                    avatar: {},
                    name_display:null,
                    email:null,
                    name:null
                }
            }
        },

        apollo: {
            me: gql`query GetCurrentUserInfo { me { name_display email name  avatar { image letters color icon placeholder } } }`
        },

        name: "page-me",
    }
</script>
