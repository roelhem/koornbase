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
                            <search-sort-input v-bind="sortInputProps" v-on="sortInputListeners" />
                        </div>

                        <div class="pl-1">
                            <search-per-page-input v-model="perPage" />
                        </div>

                    </div>
                    <!-- END: Parameters Bar -->

                    <!-- START: The Search Table -->
                    <b-card no-body>
                        <b-table v-bind="bTableProps" v-on="bTableListeners">

                            <template slot="avatar" slot-scope="{ item }">
                                <base-avatar :image="item.avatar.image"
                                             :letters="item.avatar.letters"
                                             size="md"
                                             :default-style="item.person === null ? 'user-default' : 'person-default'"
                                />
                            </template>

                            <template slot="name" slot-scope="{ item }">
                                <div>{{ item.name }}</div>
                                <div class="small text-muted">{{ item.email }}</div>
                            </template>

                            <template slot="person" slot-scope="{ item }">
                                <template v-if="item.person !== null">
                                    <div>{{ item.person.name }}</div>
                                    <div class="small text-muted-dark">
                                        <display-membership-status
                                                :status="item.person.membership_status"
                                                :since="item.person.membership_status_since"
                                                date-size="sm"
                                        />
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="text-muted font-italic">( Geen gekoppeld persoon. )</div>
                                </template>
                            </template>

                            <template slot="created_at" slot-scope="{ value }">
                                <display-timestamp :timestamp="value" />
                            </template>

                            <template slot="updated_at" slot-scope="{ value }">
                                <display-timestamp :timestamp="value" />
                            </template>

                        </b-table>
                    </b-card>
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
    import getUsersForTableQuery from '../../../graphql/queries/users.graphql';
    import SearchPerPageInput from "../../SearchPerPageInput";
    import SearchHeaderContainer from "../../SearchHeaderContainer";
    import SearchColumnSelectCard from "../../SearchColumnSelectCard";
    import SearchSortInput from "../../SearchSortInput";
    import BaseAvatar from "../../BaseAvatar";
    import DisplayMembershipStatus from "../../DisplayMembershipStatus";
    import searchTableMixin from "../../../mixins/searchTableMixin";
    import DisplayTimestamp from "../../displays/DisplayTimestamp";
    import TablerPageHeader from "../../TablerPageHeader";
    import SearchStatusDisplay from "../../SearchStatusDisplay";
    import SearchSimplePager from "../../SearchSimplePager";
    import TablerInputIcon from "../../TablerInputIcon";

    export default {
        name: 'page-users',

        searchTable: {
            queryKey: 'users',
            query: {
                query:getUsersForTableQuery,
                variables() {
                    return {
                        page: this.page,
                        limit: this.perPage,
                        orderBy: this.sortBy,
                        orderDir: this.sortDir,
                        search: this.search,
                    };
                }
            },
            columns: [
                { key:"avatar", label:"", name:"Avatar", visible:true, thStyle:{'width':'1px'} },
                { key:"id", label:"ID", visible:false, sortable:true },
                { key:"name", label:"Gebruiker", sortName: 'Gebruikersnaam', visible:true, sortable:true },
                { key:"email", label:"E-mail", visible:false, sortable:true },
                { key:"person", label:"Persoon", visible: true, sortable:true },
                { key:'created_at', label:'Aangemaakt op', sortable:true },
                { key:'updated_at', label:'Bewerkt op', name:'Laatst bewerkt op', sortable:true },
            ]
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
            TablerInputIcon,
            SearchSimplePager,
            SearchStatusDisplay,
            TablerPageHeader,
            DisplayTimestamp,
            DisplayMembershipStatus,
            BaseAvatar,
            SearchPerPageInput,
            SearchHeaderContainer,
            SearchSortInput,
            SearchColumnSelectCard
        }
    }
</script>

<style scoped>

</style>