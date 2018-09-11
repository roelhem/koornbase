<template>

    <b-container>

        <tabler-page-header title="Mijn Gegevens" />

        <b-row>


            <b-col lg="4">

                <b-card>
                    <tabler-dimmer :active="$apollo.queries.currentUser.loading">
                    <b-media>
                        <base-avatar slot="aside" size="xxl" class="mr-5" color="blue" v-bind="currentUser.avatar" />
                        <h4 class="m-0"><base-field title="Naam" name="name_display" :value="currentUser.name_display" /></h4>
                        <p class="text-muted mb-0">
                            <base-field title="Inlog E-mail" name="email" :value="currentUser.email" />
                            <small v-if="currentUser.person" class="text-muted-dark">
                                (<base-field title="Gebruikersnaam" name="name" :value="currentUser.name" class="font-italic" />)
                            </small>
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
    import { currentUser } from "../../apis/graphql/dashboard.graphql";
    import DataDisplay from "../../components/displays/DataDisplay";
    import BaseAvatar from "../../components/displays/BaseAvatar";
    import PageMePersonalData from "./overview.personal";
    import TablerDimmer from "../../components/layouts/cards/TablerDimmer";
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import BaseField from "../../components/displays/BaseField";

    export default {

        components: {
            BaseField,
            TablerPageHeader,
            TablerDimmer,
            PageMePersonalData,
            BaseAvatar,
            DataDisplay
        },

        data: function() {
            return {
                currentUser:{}
            }
        },

        apollo: {
            currentUser:currentUser
        },

        name: "page-me",
    }
</script>
