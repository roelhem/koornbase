<template>

    <div>

        <!-- START: Search Header -->
        <search-header-container v-model="page"
                                 :from="persons.from"
                                 :to="persons.to"
                                 :total="persons.total"
                                 :per-page="persons.per_page"
                                 :is-loading="isLoading"
                                 records-name="personen"
        >
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
                                <b-form-input type="search" placeholder="Zoeken..." />
                            </tabler-input-icon>
                        </b-col>

                        <b-col lg="4">
                            <search-sort-input>
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
                                 :items="persons.data"
                                 :fields="fields"
                                 :busy="isLoading"
                                 no-local-sorting>


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

                            <template slot="links" slot-scope="{ item }">
                                <router-link class="icon" :to="{name:'persons.view', params: {id: item.id} }">
                                    <base-icon icon="more-vertical" from="fe" />
                                </router-link>
                            </template>

                        </b-table>
                    </b-card>
                    <!-- END: The Search Table -->




                    <!-- START: The Bottom Paginator -->
                    <b-pagination :total-rows="persons.total"
                                  :per-page="persons.per_page"
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
                    />

                    <search-filter-group-card
                            :active.sync="filters.groups.active"
                            v-model="filters.groups.value"
                    />

                </b-col>
                <!-- END: The Sidebar Column -->


            </b-row>
        </b-container>
        <!-- END: Main Content -->

    </div>

</template>

<script>
    import SearchColumnSelectCard from "../SearchColumnSelectCard";
    import SearchFilterMembershipStatusCard from "../SearchFilterMembershipStatusCard";
    import SearchFilterGroupCard from "../SearchFilterGroupCard";
    import SearchPerPageInput from "../SearchPerPageInput";
    import SearchSortInput from "../SearchSortInput";
    import TablerInputIcon from "../TablerInputIcon";
    import BaseAvatar from "../BaseAvatar";
    import BaseIcon from "../BaseIcon";

    import { getPersonsForTableQuery } from "../../queries/persons.graphql";

    import displayFilters from '../../filters/display';
    import SearchHeaderContainer from "../SearchHeaderContainer";



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
            name:'ID',
            visible:false
        },
        {
            key:'name',
            label:'Hele Naam',
            name:'Volledige Naam',
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
        },
        {
            key:'birth_date',
            label:'Geboortedatum',
            name:'Geboortedatum',
            visible:true,
        },
        {
            key:'membership_status',
            label:'Status Lidmaatschap',
            name:'Lidstatus',
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

        apollo: {
            persons: {
                query: getPersonsForTableQuery,
                variables() {
                    return {
                        page:this.page,
                        limit:this.perPage,
                        orderByField:this.sort
                    }
                }
            },
        },

        data: function() {
            return {
                persons: {},
                columns: peopleSearchColumns,
                page: 1,
                perPage: 10,
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
                    }
                }
            }
        },

        computed: {

            fields: function() {
                return this.columns.filter(col => col.visible);
            },

            isLoading: function() {
                return this.$apollo.queries.persons.loading;
            },
        },
    }
</script>

<style scoped>

</style>