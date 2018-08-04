<template>

    <div>

        <!-- START: Search Header -->
        <search-header-container v-bind="searchHeaderProps" v-on="searchHeaderListeners">
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
                        </b-col>

                        <b-col lg="4">
                            <search-sort-input v-bind="sortInputProps" v-on="sortInputListeners" />
                        </b-col>

                        <b-col>
                            <search-per-page-input v-model="perPage" />
                        </b-col>

                    </b-form-row>
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
    import getUsersForTableQuery from '../../queries/users.graphql';
    import SearchPerPageInput from "../SearchPerPageInput";
    import SearchHeaderContainer from "../SearchHeaderContainer";
    import SearchColumnSelectCard from "../SearchColumnSelectCard";
    import SearchSortInput from "../SearchSortInput";
    import BaseAvatar from "../BaseAvatar";
    import DisplayMembershipStatus from "../DisplayMembershipStatus";
    import searchTableMixin from "../../mixins/searchTableMixin";
    import DisplayTimestamp from "../displays/DisplayTimestamp";

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
                        orderDir: this.sortDir
                    };
                }
            },
            columns: [
                { key:"avatar", label:"", name:"Avatar", visible:true, thStyle:{'width':'1px'} },
                { key:"id", label:"ID", visible:false, sortable:true },
                { key:"name", label:"Gebruiker", sortName: 'Gebruikersnaam', visible:true, sortable:true },
                { key:"email", label:"E-mail", visible:false, sortable:true },
                { key:"person", label:"Persoon", visible: true },
                { key:'created_at', label:'Aangemaakt op', sortable:true },
                { key:'updated_at', label:'Bewerkt op', name:'Laatst bewerkt op', sortable:true },
            ]
        },

        mixins:[searchTableMixin],

        data: function() {
            return {
                sidebarCards: {
                    columns: {
                        collapsed: true
                    }
                }
            }
        },

        components: {
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