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
                <tabler-card header-class="pl-3"
                             no-body
                >

                    <!-- start header -->
                    <template slot="header">
                        <base-stamp :default-style="group.category.style" />
                        <h3 class="card-title pl-3 w-100">
                            <subtile-single-input-form :value="group.name" @submit="updateNameHandler" />
                        </h3>
                    </template>
                    <!-- end header -->

                    <!-- start body -->
                    <subtile-card-body-form class="border-bottom"
                                            :value="group.description"
                                            placeholder="Geen omschrijving..."
                                            @submit="updateDescriptionHandler"
                    />

                    <detail-view in-card>


                        <subtile-detail-entry-form label="Korte naam" :value="group.name_short" @submit="updateNameShortHandler" />
                        <subtile-detail-entry-form label="Persoons-titel" :value="group.member_name" @submit="updateMemberNameHandler" />

                        <detail-entry label="Categorie">
                            <span-group-category :category="group.category" />
                        </detail-entry>

                        <detail-entry label="Tag">
                            <group-tag :group="group" label="name" />
                        </detail-entry>

                    </detail-view>
                    <!-- end body -->

                </tabler-card>
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
    import gql from 'graphql-tag';
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import { GROUPS_VIEW } from "../../apis/graphql/queries";
    import { updateGroupDescription, updateGroupNames } from "../../apis/graphql/mutations/groups.graphql";
    import TablerCard from "../../components/layouts/cards/TablerCard";
    import DetailView from "../../components/layouts/cards/DetailView";
    import DetailEntry from "../../components/layouts/cards/DetailEntry";
    import BaseStamp from "../../components/displays/BaseStamp";
    import BaseIcon from "../../components/displays/BaseIcon";
    import BaseAvatar from "../../components/displays/BaseAvatar";
    import SubtileCardBodyForm from "../../components/inputs/subtile/SubtileCardBodyForm";
    import SubtileSingleInputForm from "../../components/inputs/subtile/SubtileSingleInputForm";
    import SubtileDetailEntryForm from "../../components/inputs/subtile/SubtileDetailEntryForm";
    import SpanMembershipStatus from "../../components/displays/spans/SpanMembershipStatus";
    import SpanGroupCategory from "../../components/displays/spans/SpanGroupCategory";
    import FormSwitch from "../../components/inputs/FormSwitch";
    import GroupEmailAddressesCard from "../../components/displays/GroupEmailAddressesCard";
    import GroupPersonsCard from "../../components/displays/GroupPersonsCard";
    import GroupTag from "../../components/displays/GroupTag";

    export default {
        components: {
            GroupTag,
            GroupPersonsCard,
            GroupEmailAddressesCard,
            FormSwitch,
            SpanMembershipStatus,
            SpanGroupCategory,
            SubtileDetailEntryForm,
            SubtileSingleInputForm,
            SubtileCardBodyForm,
            BaseIcon,
            BaseAvatar,
            BaseStamp,
            DetailEntry,
            DetailView,
            TablerCard,
            TablerPageHeader
        },
        name: "view",

        apollo: {
            group: {
                query: GROUPS_VIEW,
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
                    persons:[],
                    emailAddresses:[]
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

        methods: {



            updateNameHandler(newValue) {
                const id = this.group.id;
                const name = newValue;
                const name_short = this.group.name_short;
                const member_name = this.group.member_name;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation updateGroupName($id:ID!, $name:String) {
                            updateGroup(id:$id, name:$name) {
                                id name name_short member_name
                            }
                        }
                    `,
                    variables: { id, name },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id, name, name_short, member_name
                        }
                    }
                }).then(data => console.log(data)).catch(error => console.log(error));
            },

            updateNameShortHandler(newValue) {
                const id = this.group.id;
                const name_short = newValue;
                const member_name = this.group.member_name;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation updateGroupNameShort($id:ID!, $name_short:String) {
                            updateGroup(id:$id, name_short:$name_short) {
                                id name_short member_name
                            }
                        }
                    `,
                    variables: { id, name_short },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id, name_short, member_name
                        }
                    }
                }).then(data => console.log(data)).catch(error => console.log(error));
            },

            updateMemberNameHandler(newValue) {
                const id = this.group.id;
                const member_name = newValue;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation updateGroupMemberName($id:ID!, $member_name:String) {
                            updateGroup(id:$id, member_name:$member_name) {
                                id member_name
                            }
                        }
                    `,
                    variables: { id, member_name },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id, member_name
                        }
                    }
                }).then(data => console.log(data)).catch(error => console.log(error));
            },

            updateDescriptionHandler(newValue) {
                const id = this.group.id;
                const description = newValue;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation updateGroupDescription($id:ID!, $description:String) {
                            updateGroup(id:$id, description:$description) {
                                id description
                            }
                        }
                    `,
                    variables: {id, description},

                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id,
                            description,
                        }
                    }
                }).then(data => console.log(data)).catch(error => console.log(error))
            },

        }
    }
</script>

<style scoped>



</style>