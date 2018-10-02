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
                        <b-table class="card-table" :fields="tableFields" :items="rows">

                            <template slot="avatar" slot-scope="{ item }">
                                <person-avatar :person="item" size="md" />
                            </template>

                            <template slot="name" slot-scope="{ item }">
                                <div><span-person-name :person-name="item.name" with-nickname /></div>
                                <div class="small text-muted">
                                    <span-person-name :person-name="item.name" formal />
                                </div>
                            </template>

                            <template slot="birthDate" slot-scope="{ item }">
                                <div>
                                    <base-field title="Geboortedatum" name="birthDate">{{ item.birthDate | date('bday') }}</base-field>
                                </div>
                                <div class="small text-muted">(
                                    <base-field title="Leeftijd" name="age">{{item.age }} jaar</base-field>  )</div>
                            </template>

                            <template slot="membershipStatus" slot-scope="{ item }">
                                <div>
                                    <span class="status-icon" :class="item.membershipStatus.type | membershipStatusColor "></span>
                                    <base-field title="Status Lidmaatschap" name="membershipStatus.type">{{ item.membershipStatus.type | membershipStatusName }}</base-field>
                                </div>
                                <div class="small text-muted">
                                    <base-field title="Status Lidmaatschap Sinds" name="membershipStatus.since">{{ item.membershipStatus.since | date('lg') }}</base-field>
                                </div>
                            </template>

                            <template slot="links" slot-scope="{ item }">
                                <router-link class="icon" :to="{name:'db.persons.view', params: {id: item.id} }">
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

                    <pre>
                        {{ filterValues }}
                    </pre>

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
    import DisplayTimestamp from "../../components/displays/DisplayTimestamp";
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import BaseField from "../../components/displays/BaseField";
    import SpanPersonName from "../../components/displays/spans/SpanPersonName";
    import PersonAvatar from "../../components/displays/PersonAvatar";



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
            key:'shortName',
            label: 'Naam',
            name:'Korte Naam',
            visible:false
        },
        {
            key:'nickname',
            label:'Bijnaam',
            name:'Bijnaam',
            visible:false,
            sortable:true,
        },
        {
            key:'birthDate',
            label:'Geboortedatum',
            name:'Geboortedatum',
            visible:true,
            sortable:true,
        },
        {
            key:'membershipStatus',
            label:'Status Lidmaatschap',
            name:'Lidstatus',
            visible:true,
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
            PersonAvatar,
            SpanPersonName,
            BaseField,
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
            BaseIcon
        },

        filters: displayFilters,

        name: "page-person-list",

        mixins: [searchTableMixin],

        searchTable: {
            queryKey:'persons',
            query: {
                query: gql`
                    query personsIndex {
                        persons {
                            edges {
                                node {
                                    id
                                    name { ...SpanPersonName }
                                    ...PersonAvatar
                                    membershipStatus { type since }
                                    birthDate
                                    age
                                }
                            }
                        }
                    }
                    ${SpanPersonName.fragment}
                    ${PersonAvatar.fragment}
                `,
                variables() {
                    return {
                        page:this.page,
                        limit:this.perPage,
                        orderBy:this.sortBy,
                        orderDir:this.sortDir,
                        filter:this.filterValues,
                        search:this.search
                    }
                }
            },

            columns: peopleSearchColumns,

            recordsName: 'personen',
        },

        computed: {
            rows() { return this.persons.edges.map(edge => edge.node); }
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