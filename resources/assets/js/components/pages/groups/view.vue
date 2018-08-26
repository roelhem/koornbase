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
                            <display-group-category :category="group.category" />
                        </detail-entry>

                        <detail-entry label="Tag">
                            <kb-group-tag :group="group" label="name" />
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
    import TablerPageHeader from "../../TablerPageHeader";
    import { getGroupDetailsQuery } from "../../../graphql/queries/groups.graphql";
    import { updateGroupDescription, updateGroupNames } from "../../../graphql/mutations/groups.graphql";
    import TablerCard from "../../TablerCard";
    import DetailView from "../../DetailView";
    import DetailEntry from "../../DetailEntry";
    import BaseStamp from "../../BaseStamp";
    import DisplayGroupCategory from "../../DisplayGroupCategory";
    import BaseIcon from "../../BaseIcon";
    import BaseAvatar from "../../BaseAvatar";
    import SubtileCardBodyForm from "../../forms/subtile/SubtileCardBodyForm";
    import SubtileSingleInputForm from "../../forms/subtile/SubtileSingleInputForm";
    import SubtileDetailEntryForm from "../../forms/subtile/SubtileDetailEntryForm";
    import DisplayMembershipStatus from "../../DisplayMembershipStatus";
    import FormSwitch from "../../FormSwitch";
    import GroupEmailAddressesCard from "../../GroupEmailAddressesCard";
    import GroupPersonsCard from "../../GroupPersonsCard";
    import KbGroupTag from "../../KbGroupTag";

    export default {
        components: {
            KbGroupTag,
            GroupPersonsCard,
            GroupEmailAddressesCard,
            FormSwitch,
            DisplayMembershipStatus,
            SubtileDetailEntryForm,
            SubtileSingleInputForm,
            SubtileCardBodyForm,
            BaseIcon,
            BaseAvatar,
            DisplayGroupCategory,
            BaseStamp,
            DetailEntry,
            DetailView,
            TablerCard,
            TablerPageHeader
        },
        name: "view",

        apollo: {
            group: {
                query: getGroupDetailsQuery,
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
                return this.runNameMutation({
                    id: this.group.id,
                    name: newValue,
                });
            },

            updateNameShortHandler(newValue) {
                return this.runNameMutation({
                    id: this.group.id,
                    name_short: newValue,
                });
            },

            updateMemberNameHandler(newValue) {
                return this.runNameMutation({
                    id: this.group.id,
                    member_name: newValue,
                });
            },

            runNameMutation(variables) {
                let {id, name, name_short, member_name} = Object.assign(this.defaultNameMutationParams, variables);


                this.$apollo.mutate({
                    mutation: updateGroupNames,
                    variables: variables,
                    update: (store, {data:{updateGroup: {id, name, name_short, member_name}}}) => {
                        const data = store.readQuery({query: getGroupDetailsQuery, variables:{id} });
                        data.group.name = name;
                        data.group.name_short = name_short;
                        data.group.member_name = member_name;
                        store.writeQuery({ query: getGroupDetailsQuery, data});
                    },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id, name, name_short, member_name,
                        }
                    }

                }).then(data => console.log(data)).catch(error => console.log(error));
            },

            updateDescriptionHandler(newValue) {
                const inputId = this.group.id;
                const inputDescr = newValue;

                this.$apollo.mutate({
                    mutation: updateGroupDescription,
                    variables: {
                        id: inputId,
                        description: inputDescr,
                    },

                    update: (store, {data:{ updateGroup: { id, description }}}) => {
                        const data = store.readQuery({query: getGroupDetailsQuery, variables: {id} });

                        data.group.description = description;

                        store.writeQuery({query: getGroupDetailsQuery, data });
                    },

                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id:inputId,
                            description:inputDescr,
                        }
                    }
                }).then(data => console.log(data)).catch(error => console.log(error))
            },

        }
    }
</script>

<style scoped>



</style>