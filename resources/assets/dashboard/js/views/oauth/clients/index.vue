<template>

    <div>

        <b-container>
            <tabler-page-header title="OAuth-clients zoeken"
                                :breadcrumb="[{icon:'cloud', text:'OAuth Server', active:true}, {text:'Clients',active:true}]"
            />
        </b-container>

        <search-header-container v-bind="searchHeaderProps" v-on="searchHeaderListeners">

            <new-o-auth-client-button right />

        </search-header-container>

        <b-container>

            <b-row>
                <b-col lg="9">

                    <b-form-row class="mb-2">
                        <b-col lg="6">
                            <tabler-input-icon append="search">
                                <b-form-input type="search" placeholder="Zoeken..." v-model="search" />
                            </tabler-input-icon>
                        </b-col>

                        <b-col lg="4">
                            <search-sort-input v-bind="sortInputProps" v-on="sortInputListeners" />
                        </b-col>

                        <b-col>
                            <search-per-page-input v-model="perPage" />
                        </b-col>
                    </b-form-row>

                    <o-auth-client-data-table v-bind="dataTableProps" v-on="dataTableListeners" />

                    <b-pagination v-bind="paginationProps" v-on="paginationListeners"></b-pagination>


                </b-col>

                <b-col lg="3">
                    <search-column-select-card v-model="columns" :collapsed.sync="sidebarCards.columns.collapsed" />

                    <search-filter-client-type-card v-model="sidebarCards.typeFilter.value" :active.sync="sidebarCards.typeFilter.active" />

                </b-col>
            </b-row>

        </b-container>


    </div>

</template>

<script>
    import gql from "graphql-tag";
    import SearchHeaderContainer from "../../../components/features/table-search/SearchHeaderContainer";
    import SearchColumnSelectCard from "../../../components/features/table-search/SearchColumnSelectCard";
    import TablerInputIcon from "../../../components/layouts/forms/TablerInputIcon";
    import SearchSortInput from "../../../components/features/table-search/SearchSortInput";
    import SearchPerPageInput from "../../../components/features/table-search/SearchPerPageInput";
    import searchTableMixin from "../../../mixins/searchTableMixin";
    import SearchFilterClientTypeCard from "../../../components/features/table-search/SearchFilterClientTypeCard";
    import TablerPageHeader from "../../../components/layouts/title/TablerPageHeader";
    import OAuthClientDataTable from "../../../components/displays/OAuthClientDataTable";
    import NewOAuthClientButton from "../../../components/features/new-client/NewOAuthClientButton";

    export default {
        name: "view-o-auth-clients-index",

        mixins:[searchTableMixin],

        searchTable: {
            queryKey:'clients',
            query:{
                query: gql`
                    query oauthClientsIndex($pageSize:Int = 10 $page:Int = 1) {
                        clients:oauthClients(first:$pageSize page:$page) {
                            ...SearchTableConnection
                            edges {
                                node {
                                    ...OAuthClientDataTableRow
                                }
                            }
                        }
                    }
                    ${searchTableMixin.connectionFragment}
                    ${OAuthClientDataTable.rowFragment}
                `,
                variables() {
                    return {
                        perPage: this.perPage,
                        page: this.page,
                    };
                }
            },

            defaults: {
                sortBy:'created_at',
                sortDir:'DESC'
            }
        },

        data: function() {
            return {
                search:"",
                sidebarCards: {
                    columns: {
                        collapsed: true
                    },
                    typeFilter: {
                        active: false,
                        value: []
                    }
                }
            }
        },


        computed: {

            anyType() {
                if(this.sidebarCards.typeFilter.active) {
                    return this.sidebarCards.typeFilter.value;
                }
                return null;
            }

        },

        components: {
            NewOAuthClientButton,
            OAuthClientDataTable,
            TablerPageHeader,
            SearchFilterClientTypeCard,
            SearchPerPageInput,
            SearchSortInput,
            TablerInputIcon,
            SearchColumnSelectCard,
            SearchHeaderContainer
        }
    }
</script>

<style scoped>

</style>