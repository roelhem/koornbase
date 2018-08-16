<template>
    <b-container>

        <tabler-page-header title="Groep" />

        <!-- START of the main row -->
        <b-row>



            <!-- START of the GROUP info column -->
            <b-col lg="7">

                <!-- START of the GROUP card -->
                <tabler-card header-class="pl-3"
                             no-body=""
                >

                    <!-- start header -->
                    <template slot="header">
                        <base-stamp :default-style="group.category.style" />
                        <h3 class="card-title pl-3">
                            {{ group.name }}
                            <span class="text-muted small font-italic ml-1">(Groep)</span>
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


                        <detail-entry label="Korte naam">
                            {{ group.name_short }}
                            <a href="#" style="position:absolute;right:0;bottom:0" class="btn btn-icon btn-muted">
                                <base-icon icon="edit-3" from="fe" />
                            </a>

                        </detail-entry>
                        <detail-entry label="Persoons-titel">{{ group.member_name }}</detail-entry>
                        <detail-entry label="categorie">
                            <display-group-category :category="group.category" />
                        </detail-entry>

                    </detail-view>
                    <!-- end body -->

                    <!-- start footer -->
                    <template slot="footer">
                        <div>
                            <b-button class="ml-1">Notificatie Sturen</b-button>
                            <b-button variant="danger mx-1">Verwijderen</b-button>
                        </div>
                    </template>
                    <!-- end footer -->

                </tabler-card>
                <!-- END of the GROUP card -->

            </b-col>
            <!-- END of the GROUP info column -->





            <!-- START of the PERSONS column -->
            <b-col lg="7">

            </b-col>
            <!-- END of the PERSONS column -->




        </b-row>
        <!-- END of the main row -->

    </b-container>
</template>

<script>
    import TablerPageHeader from "../../TablerPageHeader";
    import { getGroupDetailsQuery } from "../../../graphql/queries/groups.graphql";
    import { updateGroupDescription } from "../../../graphql/mutations/groups.graphql";
    import TablerCard from "../../TablerCard";
    import DetailView from "../../DetailView";
    import DetailEntry from "../../DetailEntry";
    import BaseStamp from "../../BaseStamp";
    import DisplayGroupCategory from "../../DisplayGroupCategory";
    import BaseIcon from "../../BaseIcon";
    import SubtileCardBodyForm from "../../forms/subtile/SubtileCardBodyForm";

    export default {
        components: {
            SubtileCardBodyForm,
            BaseIcon,
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
                    category: {}
                }
            };
        },

        methods: {

            updateDescriptionHandler(newValue) {
                const inputId = this.group.id;
                const inputDescr = newValue;

                this.$apollo.mutate({
                    mutation: updateGroupDescription,
                    variables: {
                        id: inputId,
                        description: inputDescr,
                    },

                    update: (store, {data:{ updateGroup: { description }}}) => {
                        const data = store.readQuery({query: getGroupDetailsQuery });

                        data.data.group.description = description;

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
                }).then(data => console.log(data))
                    .catch(error => console.log(error))
            }

        }
    }
</script>

<style scoped>



</style>