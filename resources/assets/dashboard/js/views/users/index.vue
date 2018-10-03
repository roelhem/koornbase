<template>

    <div>

        <b-container>
            <tabler-page-header title="Gebruikers"
                                no-breadcrumb
                                :breadcrumb="[{icon:'users',text:'Gebruikers',active:true}]"
            >
                <template slot="options">
                    <b-button variant="success" :to="{name:'users.create'}">Nieuwe Gebruiker</b-button>
                </template>
                <template slot="subtitle">
                    <search-status-display records-name="gebruikers"
                                           :from="from"
                                           :to="to"
                                           :total="total"
                                           :is-loading="isLoading"
                    />
                </template>
            </tabler-page-header>
        </b-container>


        <!-- START: Main Content -->
        <b-container>
            <b-row>

                <!-- START: The Main Column -->
                <b-col lg="9">


                    <!-- START: Parameters Bar -->
                    <div class="mb-2 d-flex">

                        <div class="pr-1 flex-shrink-1">
                            <search-simple-pager v-model="page" :per-page="perPage" :total="total" />
                        </div>

                        <div class="px-1 flex-grow-1">
                            <tabler-input-icon append="search">
                                <b-form-input type="search" placeholder="Zoeken..." v-model="search" />
                            </tabler-input-icon>
                        </div>

                        <div class="px-1 flex-grow-1">
                            <order-by-input v-model="orderBy" :options="['id','name']" />
                        </div>

                        <div class="pl-1">
                            <search-per-page-input v-model="perPage" />
                        </div>

                    </div>
                    <!-- END: Parameters Bar -->


                    <user-data-table v-bind="dataTableProps" v-on="dataTableListeners" />



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
    import SearchPerPageInput from "../../components/features/table-search/SearchPerPageInput";
    import SearchHeaderContainer from "../../components/features/table-search/SearchHeaderContainer";
    import SearchColumnSelectCard from "../../components/features/table-search/SearchColumnSelectCard";
    import SearchSortInput from "../../components/features/table-search/SearchSortInput";
    import searchTableMixin from "../../mixins/searchTableMixin";
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import SearchStatusDisplay from "../../components/features/table-search/SearchStatusDisplay";
    import SearchSimplePager from "../../components/features/table-search/SearchSimplePager";
    import TablerInputIcon from "../../components/layouts/forms/TablerInputIcon";
    import BaseIcon from "../../components/displays/BaseIcon";
    import UserDataTable from "../../components/displays/UserDataTable";
    import OrderByInput from "../../components/inputs/OrderByInput";

    export default {
        name: 'page-users',

        searchTable: {
            queryKey: 'users',
            query: {
                query:gql`
                    query indexUsers($pageSize:Int = 10, $page:Int = 1 $orderBy:User_orderByInput) {
                        users(first:$pageSize page:$page orderBy:$orderBy) {
                            totalCount
                            pageInfo {
                                startIndex
                                endIndex
                            }
                            edges {
                                cursor
                                node {
                                    ...UserDataTableRow
                                }
                            }
                        }
                    }
                    ${UserDataTable.rowFragment}
                `,
                variables() {
                    return {
                        page: this.page,
                        pageSize: this.perPage,
                        orderBy: this.orderBy ? this.orderBy.string : null,
                        orderDir: this.sortDir,
                        search: this.search,
                    };
                }
            },
        },

        mixins:[searchTableMixin],

        data: function() {
            return {
                search: null,
                sidebarCards: {
                    columns: {
                        collapsed: true
                    }
                }
            }
        },

        components: {
            OrderByInput,
            UserDataTable,
            BaseIcon,
            TablerInputIcon,
            SearchSimplePager,
            SearchStatusDisplay,
            TablerPageHeader,
            SearchPerPageInput,
            SearchHeaderContainer,
            SearchSortInput,
            SearchColumnSelectCard
        }
    }
</script>

<style scoped>

</style>