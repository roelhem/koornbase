<template>
    <div>
        <h1>Mijn Accountgegevens</h1>

        <user-detail-card
                :user="me"
                status="gray"
                status-left
        />

    </div>
</template>

<script>
    import gql from "graphql-tag";
    import UserDetailCard from "../../components/displays/UserDetailCard";

    export default {
        name: "view-me-overview-user",

        components: {
            UserDetailCard
        },

        apollo: {
            me:{
                query:gql`
                    query viewMeOverviewUser {
                        me {
                            ...UserDetailCard
                        }
                    }
                    ${UserDetailCard.fragment}
                `,
            }
        },

        data() {
            return {
                me:{}
            }
        }
    }
</script>

<style scoped>

</style>