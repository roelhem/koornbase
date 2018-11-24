<template>

    <div>

        <b-container>
            <tabler-page-header
                    title="Personen zoeken"
                    :breadcrumb="[{icon:'database',text:'Administratie',active:true}, {text:'Personen',active:true}]"
            />
        </b-container>

        <!-- START: Search Header -->
        <search-header-container v-bind="searchHeaderProps" v-on="searchHeaderListeners">
            <b-button variant="success" href="#">
                <base-icon :icon="{fa:'plus',fe:'plus'}"
                           :from="['fe','fa']"
                           class="mr-2" />
                Persoon Toevoegen
            </b-button>
        </search-header-container>
        <!-- END: Search Header -->


        <!-- START: Main Content -->
        <b-container>
            <b-row>

                <!-- START: The Main Column -->
                <b-col lg="9">


                    <!-- START: Parameters Bar -->
                    <b-form-row class="mb-2">

                        <b-col lg="6">
                            <tabler-input-icon append="search">
                                <b-form-input type="search" placeholder="Zoeken..." v-model="search" />
                            </tabler-input-icon>
                        </b-col>

                        <b-col lg="4">
                            <order-by-input v-model="orderBy" :options="['id','firstName','lastName','createdAt','birthDate']" />
                        </b-col>

                        <b-col>
                            <search-per-page-input v-model="perPage" />
                        </b-col>

                    </b-form-row>
                    <!-- END: Parameters Bar -->

                    <!-- START: The Search Table -->
                    <person-data-table v-bind="dataTableProps" v-on="dataTableListeners" />
                    <!-- END: The Search Table -->




                    <!-- START: The Bottom Paginator -->
                    <b-pagination v-bind="paginationProps" v-on="paginationListeners">
                    </b-pagination>
                    <!-- END: The Bottom Paginator -->




                </b-col>
                <!-- END: The Main Column -->


                <!-- START: The Sidebar Column -->
                <b-col lg="3">
                    <search-column-select-card v-model="columns" :collapsed.sync="sidebarCards.columns.collapsed" />
                </b-col>
                <!-- END: The Sidebar Column -->


            </b-row>
        </b-container>
        <!-- END: Main Content -->

    </div>

</template>

<script>
    import gql from "graphql-tag";

    import SearchColumnSelectCard from "../../components/features/table-search/SearchColumnSelectCard";
    import SearchFilterMembershipStatusCard from "../../components/features/table-search/SearchFilterMembershipStatusCard";
    import SearchFilterGroupCard from "../../components/features/table-search/SearchFilterGroupCard";
    import SearchPerPageInput from "../../components/features/table-search/SearchPerPageInput";
    import SearchSortInput from "../../components/features/table-search/SearchSortInput";
    import TablerInputIcon from "../../components/layouts/forms/TablerInputIcon";
    import BaseIcon from "../../components/displays/BaseIcon";
    import searchTableMixin from "../../mixins/searchTableMixin";

    import displayFilters from '../../utils/filters/display';
    import SearchHeaderContainer from "../../components/features/table-search/SearchHeaderContainer";
    import SearchFilterTimestampCard from "../../components/features/table-search/SearchFilterTimestampCard";
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import PersonDataTable from "../../components/displays/PersonDataTable";
    import OrderByInput from "../../components/inputs/OrderByInput";

    export default {
        components: {
            OrderByInput,
            PersonDataTable,
            TablerPageHeader,
            SearchFilterTimestampCard,
            SearchHeaderContainer,
            SearchColumnSelectCard,
            SearchFilterMembershipStatusCard,
            SearchFilterGroupCard,
            SearchPerPageInput,
            SearchSortInput,
            TablerInputIcon,
            BaseIcon
        },

        filters: displayFilters,

        name: "page-person-list",

        mixins: [searchTableMixin],

        searchTable: {
            queryKey:'persons',
            query: {
                query: gql`
                    query personsIndex($pageSize:Int = 10 $page:Int = 1 $orderBy:Person_orderByInput = id_ASC) {
                        persons(first:$pageSize page:$page orderBy:$orderBy) {
                            ...SearchTableConnection
                            edges {
                                node {
                                    ...PersonDataTableRow
                                }
                            }
                        }
                    }
                    ${PersonDataTable.rowFragment}
                    ${searchTableMixin.connectionFragment}
                `,
                variables() {
                    return {
                        page:this.page,
                        pageSize:this.perPage,
                        orderBy:this.orderBy ? this.orderBy.string : null,
                    }
                }
            },

            recordsName: 'personen',
        },

        data: function() {
            return {
                search:null,
                sidebarCards: {
                    columns: {
                        collapsed:true,
                    },
                },
                filters: {
                    membershipStatus: {
                        active: false,
                        value: []
                    },
                    groups: {
                        active: false,
                        value: []
                    },
                    timestamps: {
                        active: false,
                        value: {
                            createdBefore:null,
                            createdAfter:null,
                            updatedBefore:null,
                            updatedAfter:null
                        }
                    }
                }
            }
        },
    }
</script>

<style scoped>

</style>