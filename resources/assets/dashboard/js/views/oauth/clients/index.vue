<template>

    <div>

        <b-container>
            <tabler-page-header title="OAuth-clients zoeken"
                                :breadcrumb="[{icon:'cloud', text:'OAuth Server', active:true}, {text:'Clients',active:true}]"
            />
        </b-container>

        <search-header-container v-bind="searchHeaderProps" v-on="searchHeaderListeners">

            <b-button variant="success" :to="{name: 'oauth.clients.create'}">Client Toevoegen</b-button>

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

                    <b-card no-body>
                        <b-table v-bind="bTableProps" v-on="bTableListeners">

                            <template slot="revoked" slot-scope="{ item }">
                                <span v-b-tooltip.hover :title="item.revoked ? 'Contact met server ingetrokken.' : 'In contact met server.' " class="fa-stack">
                                    <base-icon :class="item.revoked ? 'text-yellow' : 'text-cyan'"
                                               class="fa-stack-2x"
                                               icon="circle"
                                               from="fa"
                                    />
                                    <base-icon v-if="item.revoked"
                                               class="fa-stack-1x fa-inverse fa-rotate-270"
                                               icon="hand-stop-o"
                                               from="fa"
                                    />
                                    <base-icon v-else
                                               class="fa-stack-1x fa-inverse"
                                               icon="handshake-o"
                                               from="fa"
                                    />
                                </span>
                            </template>

                            <template slot="name" slot-scope="{ item }">
                                <span :class="{ 'text-muted font-italic':item.revoked }">{{ item.name }}</span>
                            </template>

                            <template slot="type" slot-scope="{ item }">
                                <display-o-auth-client-type :type="item.type" :revoked="item.revoked" />
                            </template>

                            <template slot="redirect" slot-scope="{ item }">
                                <span v-if="item.redirect" class="small" :class="{'text-muted':item.revoked}">{{ item.redirect }}</span>
                                <span v-else class="small font-italic text-muted">(Geen redirect nodig)</span>
                            </template>

                            <template slot="user" slot-scope="{ item }">
                                <template v-if="item.user">{{ item.user.name_display }}</template>
                                <span v-else class="text-muted font-italic">(Geen)</span>
                            </template>



                            <template slot="created_at" slot-scope="{ value }">
                                <display-timestamp :timestamp="value" />
                            </template>

                            <template slot="updated_at" slot-scope="{ value }">
                                <display-timestamp :timestamp="value" />
                            </template>

                            <template slot="actions" slot-scope="{ item }">
                                <router-link class="icon" :to="{name:'oauth.clients.view', params: {id: item.id} }">
                                    <base-icon icon="more-vertical" from="fe" />
                                </router-link>
                            </template>
                        </b-table>
                    </b-card>


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

    import { oAuthClientIndex } from '../../../apis/graphql/dashboard.graphql';
    import SearchHeaderContainer from "../../../components/features/table-search/SearchHeaderContainer";
    import SearchColumnSelectCard from "../../../components/features/table-search/SearchColumnSelectCard";
    import CreateOAuthClientForm from "../../../components/features/crud/CreateOAuthClientForm";
    import BaseIcon from "../../../components/displays/BaseIcon";
    import DisplayOAuthClientType from "../../../components/displays/DisplayOAuthClientType";
    import TablerInputIcon from "../../../components/layouts/forms/TablerInputIcon";
    import SearchSortInput from "../../../components/features/table-search/SearchSortInput";
    import SearchPerPageInput from "../../../components/features/table-search/SearchPerPageInput";
    import searchTableMixin from "../../../mixins/searchTableMixin";
    import DisplayTimestamp from "../../../components/displays/DisplayTimestamp";
    import SearchFilterClientTypeCard from "../../../components/features/table-search/SearchFilterClientTypeCard";
    import TablerPageHeader from "../../../components/layouts/title/TablerPageHeader";

    export default {
        name: "page-o-auth-clients",

        mixins:[searchTableMixin],

        searchTable: {
            queryKey:'clients',
            query:{
                query:oAuthClientIndex,
                variables() {
                    return {
                        perPage: this.perPage,
                        page: this.page,
                        anyType: this.anyType,
                        search: this.search,
                        orderBy: this.sortBy,
                        orderDir: this.sortDir,
                    };
                }
            },


            columns: [
                {
                    key:'id',
                    label:'ID',
                    visible:true,
                    sortable:true,
                },
                {
                    key:'revoked',
                    label:'',
                    name:'Huidige client-status',
                    visible:true,
                    sortable:true,
                    thStyle:{'width':'1px'}
                },
                {
                    key:'name',
                    label:'Naam',
                    visible:true,
                    sortable:true,
                },
                {
                    key:'redirect',
                    label:'Redirect URL',
                },
                {
                    key:'type',
                    label:'Type',
                    name:'OAuth-Client Type',
                    visible:true,
                    sortable:true,
                },
                {
                    key:'user',
                    label:'Eigenaar',
                    name:'Eigenaar/Beheerder',
                    visible: true,
                },
                {
                    key:'created_at',
                    label:'Aangemaakt op',
                    sortable: true,
                },
                {
                    key:'updated_at',
                    label:'Bewerkt op',
                    name:'Laatst bewerkt op',
                    sortable: true,
                },
                {
                    key:'actions',
                    label:'',
                    name:'Acties',
                    thStyle:{'width':'1px'},
                    visible:true
                }
            ],

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
            TablerPageHeader,
            SearchFilterClientTypeCard,
            DisplayTimestamp,
            SearchPerPageInput,
            SearchSortInput,
            TablerInputIcon,
            DisplayOAuthClientType,
            BaseIcon,
            CreateOAuthClientForm,
            SearchColumnSelectCard,
            SearchHeaderContainer
        }
    }
</script>

<style scoped>

</style>