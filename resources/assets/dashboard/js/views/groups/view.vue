<template>
    <b-container>

        <tabler-page-header no-breadcrumb>
            <template slot="title">
                Groep '<em>{{ group.name }}</em>'
            </template>
        </tabler-page-header>

        <!-- START of the main row -->
        <b-row>



            <!-- START of the GROUP info column -->
            <b-col lg="7">

                <!-- START of the GROUP card -->
                <group-card :group="group" />
                <!-- END of the GROUP card -->


                <!-- START of the EMAIL ADDRESSES card -->
                <group-email-addresses-card :group="group" />
                <!-- END of the EMAIL ADDRESSES card -->


            </b-col>
            <!-- END of the GROUP info column -->





            <!-- START of the PERSONS column -->
            <b-col lg="5">

                <group-persons-card :group="group" />

            </b-col>
            <!-- END of the PERSONS column -->




        </b-row>
        <!-- END of the main row -->

    </b-container>
</template>

<script>
    import gql from "graphql-tag";
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import GroupEmailAddressesCard from "../../components/displays/GroupEmailAddressesCard";
    import GroupPersonsCard from "../../components/displays/GroupPersonsCard";
    import GroupCard from "../../components/displays/GroupCard";

    export default {
        components: {
            GroupPersonsCard,
            GroupEmailAddressesCard,
            TablerPageHeader,
            GroupCard
        },
        name: "view-groups-view",

        apollo: {
            group: {
                query: gql`
                    query viewGroup($id:ID!) {
                        group(id:$id) {
                            ...GroupCard
                            ...GroupPersonsCard
                            ...GroupEmailAddressesCard
                        }
                    }
                    ${GroupCard.fragment}
                    ${GroupPersonsCard.fragment}
                    ${GroupEmailAddressesCard.fragment}
                `,
                variables() {
                    return {
                        id:this.id
                    };
                }
            }
        },

        props: {
            id: {
                type:[Number,String],
                required:true,
            }
        },

        data() {
            return {
                group: {
                    category: {},
                    persons:{data:[],total:0},
                    emailAddresses:{data:[],total:0}
                }
            };
        },

        computed: {
            defaultNameMutationParams() {
                return {
                    id: this.group.id,
                    name: this.group.name,
                    name_short: this.group.name_short,
                    member_name: this.group.member_name,
                }
            }
        },
    }
</script>

<style scoped>



</style>