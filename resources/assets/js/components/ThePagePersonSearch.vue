<template>

    <div>

        <!-- START: Global Toolbar -->
        <b-container>

            <b-row>

                <!-- START: Small Pager -->
                <b-col md="auto">
                    <search-simple-pager v-if="meta.last_page" v-model="page" :lastPage="meta.last_page" />
                </b-col>
                <!-- END: Small Pager -->


                <!-- START: Small Pager -->
                <b-col align-self="center">
                    <search-status-display
                            records-name="personen"
                            :is-loading="isLoading"
                            :has-error="hasError"
                            :meta="meta" />
                </b-col>
                <!-- END: Small Pager -->


                <!-- START: Other Actions -->
                <b-col md="auto">
                    <b-button variant="success" href="/people/create">
                        <base-icon :icon="{fa:'plus',fe:'plus'}" :from="['fe','fa']" class="mr-2"></base-icon>
                        Persoon Toevoegen
                    </b-button>
                </b-col>
                <!-- END: Other Actions -->


            </b-row>

            <hr class="my-3" />

        </b-container>
        <!-- END: Global Toolbar -->


        <!-- START: Main Content -->
        <b-container>
            <b-row>

                <!-- START: The Main Column -->
                <b-col lg="9">


                    <!-- START: Parameters Bar -->
                    <b-form-row class="mb-2">

                        <b-col lg="6">
                            <tabler-input-icon append="search">
                                <b-form-input type="search" placeholder="Zoeken..." />
                            </tabler-input-icon>
                        </b-col>

                        <b-col lg="4">
                            <search-sort-input v-model="sort" :sortOrder.sync="sortOrder">
                                <option value="null" disabled>-- Sorteren op --</option>
                                <optgroup label="Naam">
                                    <option value="name_first">Voornaam</option>
                                    <option value="name_last">Achternaam</option>
                                    <option value="name_nickname">Bijnaam</option>
                                </optgroup>
                                <optgroup label="Persoonlijk">
                                    <option value="birth_date">Geboortedatum</option>
                                </optgroup>
                                <optgroup label="O.J.V. de Koornbeurs">
                                    <option value="membership_status">Lidstatus</option>
                                </optgroup>
                                <optgroup label="KoornBase Systeem">
                                    <option value="id">Primary Key (ID)</option>
                                </optgroup>
                            </search-sort-input>
                        </b-col>

                        <b-col>
                            <search-per-page-input v-model="perPage" />
                        </b-col>

                    </b-form-row>
                    <!-- END: Parameters Bar -->

                    <!-- START: The Search Table -->
                    <b-card no-body>
                        <b-table id="people_search_table" ref="searchTable" class="card-table"
                                 :items="itemsProvider"
                                 :fields="tableFields"
                                 :current-page="page"
                                 :per-page="perPage"
                                 :busy.sync="isLoading"
                                 :sort-by.sync="sortByColumn"
                                 :sort-desc.sync="sortDesc">


                            <template slot="avatar" slot-scope="{ item }">
                                <base-avatar v-bind="item.avatar" size="md" default-style="person-default" />
                            </template>

                            <template slot="name" slot-scope="{ item }">
                                <div>{{ item.name.full }}</div>
                                <div class="small text-muted">
                                    {{ item.name.initials }}
                                    {{ item.name.prefix }}
                                    {{ item.name.last }}
                                </div>
                            </template>

                            <template slot="birth_date" slot-scope="{ item }">
                                <div>{{ item.birth_date | date('bday') }}</div>
                                <div class="small text-muted">( {{item.birth_date | age }} jaar )</div>
                            </template>

                            <template slot="membership_status" slot-scope="{ item }">
                                <div>
                                    <span class="status-icon" :class="item.membership.status | membershipStatusColor "></span>
                                    {{ item.membership.status | membershipStatusName }}
                                </div>
                                <div class="small text-muted">
                                    {{ item.membership.since | date('lg') }}
                                </div>
                            </template>

                            <template slot="links" slot-scope="{ item }">
                                <a class="icon" :href="'/people/'+item.id">
                                    <base-icon icon="more-vertical" from="fe"></base-icon>
                                </a>
                            </template>

                        </b-table>
                    </b-card>
                    <!-- END: The Search Table -->




                    <!-- START: The Bottom Paginator -->
                    <b-pagination :total-rows="meta.total"
                                  :per-page="perPage"
                                  :limit="11"
                                  v-model="page">
                    </b-pagination>
                    <!-- END: The Bottom Paginator -->




                </b-col>
                <!-- END: The Main Column -->


                <!-- START: The Sidebar Column -->
                <b-col lg="3">
                    <search-column-select-card v-model="columns" :collapsed.sync="sidebarCards.columns.collaped" />

                    <search-filter-membership-status-card
                        :active.sync="filters.membershipStatus.active"
                        v-model="filters.membershipStatus.value"
                        @input="refreshTable()" v-on:update:active="refreshTable()"
                    />

                    <search-filter-group-card
                            :active.sync="filters.groups.active"
                            v-model="filters.groups.value"
                            @input="refreshTable()" v-on:update:active="refreshTable()"
                    />

                </b-col>
                <!-- END: The Sidebar Column -->


            </b-row>
        </b-container>
        <!-- END: Main Content -->

    </div>

</template>

<script>
    import controlSearchTable from '../mixins/controlSearchTable';
    import SearchColumnSelectCard from "./SearchColumnSelectCard";
    import SearchPerPageInput from "./SearchPerPageInput";
    import SearchSortInput from './SearchSortInput';
    import TablerInputIcon from "./TablerInputIcon";
    import BaseIcon from "./BaseIcon";
    import SearchSimplePager from "./SearchSimplePager";
    import SearchStatusDisplay from "./SearchStatusDisplay";

    import displayFilters from "../filters/display";
    import SearchFilterMembershipStatusCard from "./SearchFilterMembershipStatusCard";
    import SearchFilterGroupCard from "./SearchFilterGroupCard";
    import RefTagGroup from "./RefTagGroup";


    const peopleSearchColumns = [
        {
            key:'avatar',
            label: '',
            name:'Avatar',
            visible:true,
            thStyle:{'width':'1px'},
            tdClass:['p-3']
        },
        {
            key:'id',
            label:'ID',
            name:'ID',
            sortable:'id',
            visible:false
        },
        {
            key:'name',
            label:'Hele Naam',
            name:'Volledige Naam',
            sortable:'name_last',
            visible:true,
            formatter:function(value, key, item) {
                return item.name.full;
            }
        },
        {
            key:'name.short',
            label: 'Naam',
            name:'Korte Naam',
            sortable:'name_first',
            visible:false
        },
        {
            key:'name.nickname',
            label:'Bijnaam',
            name:'Bijnaam',
            sortable:'name_nickname',
            visible:false,
        },
        {
            key:'birth_date',
            label:'Geboortedatum',
            name:'Geboortedatum',
            sortable:'birth_date',
            visible:true,
        },
        {
            key:'membership_status',
            label:'Status Lidmaatschap',
            name:'Lidstatus',
            sortable:'membership_status',
            visible:true
        },
        {
            key:'links',
            label:'',
            name:'Actieknoppen',
            thStyle:{'width':'1px'},
            visible:true,
        }
    ];


    export default {
        components: {
            RefTagGroup,
            SearchFilterGroupCard,
            SearchFilterMembershipStatusCard,
            SearchStatusDisplay,
            SearchSimplePager,
            BaseIcon,
            TablerInputIcon,
            SearchPerPageInput,
            SearchColumnSelectCard,
            SearchSortInput
        },
        name: "the-page-person-search",

        mixins:[ controlSearchTable ],
        filters: displayFilters,

        data: function() {
            return {
                columns: peopleSearchColumns,
                sort:'id',
                sidebarCards: {
                    columns: {
                        collaped:true,
                    }
                },
                filters: {
                    membershipStatus: {
                        active:false,
                        value:[]
                    },
                    groups: {
                        active:false,
                        value:[]
                    }
                }
            }
        },

        computed: {
            endpoint: function() {
                return '/people/search';
            },

            params: function() {
                let res = this.getDefaultParams();

                if(this.filters.membershipStatus.active) {
                    res.membership_status = this.filters.membershipStatus.value;
                }

                if(this.filters.groups.active) {
                    res.groups = this.filters.groups.value.map(el => el.id);
                }

                return res;
            }
        },

        methods: {
            refreshTable() {
                this.$refs.searchTable.refresh();
            }
        }

    }
</script>

<style scoped>

</style>