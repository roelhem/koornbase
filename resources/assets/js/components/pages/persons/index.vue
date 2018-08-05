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
                                <base-avatar v-bind="item.avatar"
                                             size="md"
                                             default-style="person-default"
                                />
                            </template>

                            <template slot="name" slot-scope="{ item }">
                                <div>{{ item.name }}</div>
                                <div class="small text-muted">
                                    {{ item.name_formal }}
                                </div>
                            </template>

                            <template slot="birth_date" slot-scope="{ item }">
                                <div>{{ item.birth_date | date('bday') }}</div>
                                <div class="small text-muted">( {{item.age }} jaar )</div>
                            </template>

                            <template slot="membership_status" slot-scope="{ item }">
                                <div>
                                    <span class="status-icon" :class="item.membership_status | membershipStatusColor "></span>
                                    {{ item.membership_status | membershipStatusName }}
                                </div>
                                <div class="small text-muted">
                                    {{ item.membership_status_since | date('lg') }}
                                </div>
                            </template>

                            <template slot="created_at" slot-scope="{ value }">
                                <display-timestamp :timestamp="value" />
                            </template>

                            <template slot="updated_at" slot-scope="{ value }">
                                <display-timestamp :timestamp="value" />
                            </template>

                            <template slot="links" slot-scope="{ item }">
                                <router-link class="icon" :to="{name:'persons.view', params: {id: item.id} }">
                                    <base-icon icon="more-vertical" from="fe" />
                                </router-link>
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

                    <search-filter-membership-status-card
                            :active.sync="filters.membershipStatus.active"
                            v-model="filters.membershipStatus.value"
                    />

                    <search-filter-group-card
                            :active.sync="filters.groups.active"
                            v-model="filters.groups.value"
                    />

                    <search-filter-timestamp-card
                            :active.sync="filters.timestamps.active"
                            v-model="filters.timestamps.value"
                    />

                    <pre>
                        {{ filters }}
                    </pre>

                </b-col>
                <!-- END: The Sidebar Column -->


            </b-row>
        </b-container>
        <!-- END: Main Content -->

    </div>

</template>

<script>
    import SearchColumnSelectCard from "../../SearchColumnSelectCard";
    import SearchFilterMembershipStatusCard from "../../SearchFilterMembershipStatusCard";
    import SearchFilterGroupCard from "../../SearchFilterGroupCard";
    import SearchPerPageInput from "../../SearchPerPageInput";
    import SearchSortInput from "../../SearchSortInput";
    import TablerInputIcon from "../../TablerInputIcon";
    import BaseAvatar from "../../BaseAvatar";
    import BaseIcon from "../../BaseIcon";
    import searchTableMixin from "../../../mixins/searchTableMixin";

    import { getPersonsForTableQuery } from "../../../queries/persons.graphql";

    import displayFilters from '../../../filters/display';
    import SearchHeaderContainer from "../../SearchHeaderContainer";
    import SearchFilterTimestampCard from "../../SearchFilterTimestampCard";
    import DisplayTimestamp from "../../displays/DisplayTimestamp";
    import TablerPageHeader from "../../TablerPageHeader";



    const peopleSearchColumns = [
        {
            key:'avatar',
            label: '',
            name:'Avatar',
            visible:true,
            thStyle:{'width':'1px'},
            tdClass:'p-3'
        },
        {
            key:'id',
            label:'ID',
            visible:false,
            sortable:true,
        },
        {
            key:'name',
            label:'Hele Naam',
            name:'Volledige Naam',
            sortable:true,
            visible:true,
            formatter:function(value, key, item) {
                return item.name;
            }
        },
        {
            key:'name_short',
            label: 'Naam',
            name:'Korte Naam',
            visible:false
        },
        {
            key:'name_nickname',
            label:'Bijnaam',
            name:'Bijnaam',
            visible:false,
            sortable:true,
        },
        {
            key:'birth_date',
            label:'Geboortedatum',
            name:'Geboortedatum',
            visible:true,
            sortable:true,
        },
        {
            key:'membership_status',
            label:'Status Lidmaatschap',
            name:'Lidstatus',
            visible:true,
            sortable:true,
        },
        {
            key:'created_at',
            label:'Aangemaakt op',
            sortable:true,
        },
        {
            key:'updated_at',
            label:'Bewerkt op',
            name:'Laatst bewerkt op',
            sortable:true,
        },
        {
            key:'links',
            label:'',
            name:'Actieknoppen',
            thStyle:{'width':'1px'},
            visible:true,
        },
    ];

    export default {
        components: {
            TablerPageHeader,
            DisplayTimestamp,
            SearchFilterTimestampCard,
            SearchHeaderContainer,
            SearchColumnSelectCard,
            SearchFilterMembershipStatusCard,
            SearchFilterGroupCard,
            SearchPerPageInput,
            SearchSortInput,
            TablerInputIcon,
            BaseAvatar,
            BaseIcon
        },

        filters: displayFilters,

        name: "page-person-list",

        mixins: [searchTableMixin],

        searchTable: {
            queryKey:'persons',
            query: {
                query: getPersonsForTableQuery,
                variables() {
                    return {
                        page:this.page,
                        limit:this.perPage,
                        orderBy:this.sortBy,
                        orderDir:this.sortDir,
                        search:this.search,
                        anyMembershipStatus:this.anyMembershipStatus
                    }
                }
            },

            columns: peopleSearchColumns,

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

        computed: {

            anyMembershipStatus() {
                if(this.filters.membershipStatus.active) {
                    return this.filters.membershipStatus.value;
                } else {
                    return null;
                }
            }

        }
    }
</script>

<style scoped>

</style>