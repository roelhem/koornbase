<template>

    <b-container>

        <tabler-page-header title="Mijn Gegevens" />

        <b-row>


            <b-col lg="4">

                <user-media-card :user="currentUser" />

                <b-list-group>
                    <b-list-group-item
                            :to="{ name:'me.overview.user' }"
                    >Mijn Accountgegevens</b-list-group-item>
                    <b-list-group-item
                            :to="{ name:'me.overview.personal' }"
                            :disabled="!currentUser.person"
                    >Mijn Persoonsgegevens</b-list-group-item>
                    <b-list-group-item
                            :to="{ name:'me.overview.koornbeurs' }"
                            :disabled="!currentUser.person"
                    >Mijn Koornbeurs</b-list-group-item>
                </b-list-group>

            </b-col>


            <b-col lg="8">
                <router-view />

            </b-col>


        </b-row>

    </b-container>

</template>

<script>
    import { CURRENT_USER } from "../../apis/graphql/queries";
    import DataDisplay from "../../components/displays/DataDisplay";
    import BaseAvatar from "../../components/displays/BaseAvatar";
    import PageMePersonalData from "./overview.personal";
    import TablerDimmer from "../../components/layouts/cards/TablerDimmer";
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import BaseField from "../../components/displays/BaseField";
    import UserMediaCard from "../../components/displays/UserMediaCard";

    export default {

        components: {
            UserMediaCard,
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
            currentUser:CURRENT_USER
        },

        name: "view-me-overview",
    }
</script>
