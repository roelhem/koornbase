<template>

    <b-container>

        <tabler-page-header title="Mijn Gegevens" />

        <b-row>


            <b-col lg="4">

                <user-media-card :user="me" />

                <b-list-group>
                    <b-list-group-item
                            :to="{ name:'me.overview.user' }"
                    >Mijn Accountgegevens</b-list-group-item>
                    <b-list-group-item
                            :to="{ name:'me.overview.personal' }"
                            :disabled="!me.person"
                    >Mijn Persoonsgegevens</b-list-group-item>
                    <b-list-group-item
                            :to="{ name:'me.overview.koornbeurs' }"
                            :disabled="!me.person"
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
    import gql from "graphql-tag";
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import UserMediaCard from "../../components/displays/UserMediaCard";

    export default {

        components: {
            UserMediaCard,
            TablerPageHeader,
        },

        data: function() {
            return {
                me:{}
            }
        },

        apollo: {
            me:gql`
                query meOverview {
                    me {
                        id
                        ...UserMediaCard
                        person {
                            id
                        }
                    }
                }
                ${UserMediaCard.fragment}
            `,
        },

        name: "view-me-overview",
    }
</script>
