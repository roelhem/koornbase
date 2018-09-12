<template>
    <div>
        <h1>Mijn Accountgegevens</h1>

        <user-detail-card
                :user="currentUser"
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
            currentUser:{
                query:gql`
                    query viewMeOverviewUser {
                        currentUser:me {
                            ...UserDetailCard
                        }
                    }
                    ${UserDetailCard.fragment}
                `,
            }
        },

        data() {
            return {
                currentUser:{}
            }
        }
    }
</script>

<style scoped>

</style>