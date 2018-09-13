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
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import UserMediaCard from "../../components/displays/UserMediaCard";

    export default {

        components: {
            UserMediaCard,
            TablerPageHeader,
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
