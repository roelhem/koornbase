<template>

    <b-container>

        <tabler-page-header no-breadcrumb>
            <template slot="title">
                Gebruiker <em>'{{ user.name }}'</em>
            </template>
        </tabler-page-header>




        <b-row>

            <b-col lg="6">
                <user-detail-card :user="user" />
            </b-col>


            <b-col lg="6">

            </b-col>


        </b-row>

    </b-container>

</template>

<script>
    import gql from "graphql-tag";
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import UserDetailCard from "../../components/displays/UserDetailCard";

    export default {
        name: "view-users-view",


        components: {
            UserDetailCard,
            TablerPageHeader
        },


        apollo: {
            user:{
                query:gql`
                    query viewUser($id:ID!) {
                        user(id:$id) {
                            ...UserDetailCard
                        }
                    }
                    ${UserDetailCard.fragment}
                `,
                variables() {
                    return {
                        id:this.id
                    };
                }
            }
        },


        data() {
            return {
                user:{}
            }
        },

        props: {
            id:{
                type:[Number,String],
                required:true,
            }
        }
    }
</script>

<style scoped>

</style>