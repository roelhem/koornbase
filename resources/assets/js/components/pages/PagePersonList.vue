<template>

    <div>

        <b-container>

            <b-row>

                <!-- START: Small Pager -->
                <b-col md="auto">
                    <search-simple-pager v-if="meta.last_page"
                                         v-model="page"
                                         :lastPage="meta.last_page" />
                </b-col>
                <!-- END: Small Pager -->


                <!-- START: Search Status -->
                <b-col align-self="center">
                    <search-status-display records-name="personen"
                                           :is-loading="isLoading"
                                           :has-error="hasError"
                                           :meta="meta" />
                </b-col>
                <!-- END: Search Status -->


                <!-- START: Other Actions -->
                <b-col md="auto">
                    <b-button variant="success" href="#">
                        <base-icon :icon="{fa:'plus',fe:'plus'}"
                                   :from="['fe','fa']"
                                   class="mr-2" />
                        Persoon Toevoegen
                    </b-button>
                </b-col>
                <!-- END: Other Actions -->


            </b-row>

            <hr class="my-3" />
        </b-container>


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
                                <div>{{ item.name }}</div>
                                <div class="small text-muted">
                                    {{ item.name_formal }}
                                </div>
                            </template>

                            <template slot="birth_date" slot-scope="{ item }">
                                <div>{{ item.birth_date | date('bday') }}</div>
                                <div class="small text-muted">( {{item.birth_date | age }} jaar )</div>
                            </template>

                            <template slot="membership_status" slot-scope="{ item }">
                                <div>
                                    <span class="status-icon" :class="item.membership_status.status | membershipStatusColor "></span>
                                    {{ item.membership_status.status | membershipStatusName }}
                                </div>
                                <div class="small text-muted">
                                    {{ item.membership_status.since | date('lg') }}
                                </div>
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

                    <!--<search-filter-membership-status-card
                            :active.sync="filters.membershipStatus.active"
                            v-model="filters.membershipStatus.value"
                            @input="refreshTable()" v-on:update:active="refreshTable()"
                    />

                    <search-filter-group-card
                            :active.sync="filters.groups.active"
                            v-model="filters.groups.value"
                            @input="refreshTable()" v-on:update:active="refreshTable()"
                    />-->

                </b-col>
                <!-- END: The Sidebar Column -->


            </b-row>
        </b-container>
        <!-- END: Main Content -->

    </div>

</template>

<script>
    import SearchStatusDisplay from "../SearchStatusDisplay";
    import SearchSimplePager from "../SearchSimplePager";
    import SearchColumnSelectCard from "../SearchColumnSelectCard";
    import SearchFilterMembershipStatusCard from "../SearchFilterMembershipStatusCard";
    import SearchFilterGroupCard from "../SearchFilterGroupCard";
    import SearchPerPageInput from "../SearchPerPageInput";
    import SearchSortInput from "../SearchSortInput";
    import TablerInputIcon from "../TablerInputIcon";
    import BaseAvatar from "../BaseAvatar";
    import BaseIcon from "../BaseIcon";

    import controlSearchTable from '../../mixins/controlSearchTable';
    import displayFilters from '../../filters/display';



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
            sortable:'name',
            visible:true,
            formatter:function(value, key, item) {
                return item.name;
            }
        },
        {
            key:'name_short',
            label: 'Naam',
            name:'Korte Naam',
            sortable:'name_first',
            visible:false
        },
        {
            key:'name_nickname',
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
            SearchSimplePager,
            SearchStatusDisplay,
            SearchColumnSelectCard,
            SearchFilterMembershipStatusCard,
            SearchFilterGroupCard,
            SearchPerPageInput,
            SearchSortInput,
            TablerInputIcon,
            BaseAvatar,
            BaseIcon
        },

        mixins: [controlSearchTable],
        filters: displayFilters,

        name: "page-person-list",

        data: function() {
            return {
                columns: peopleSearchColumns,
                sort:'id',
                sidebarCards: {
                    columns: {
                        collapsed:true,
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
                return '/api/persons';
            },

            params: function() {
                let res = this.getDefaultParams();

                res.fields = ['membership_status','avatar'];

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
        },
    }
</script>

<style scoped>

</style>