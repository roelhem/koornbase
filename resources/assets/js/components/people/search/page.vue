<template>
    <b-container>

        <b-form-row>

            <b-col>

                <b-button-toolbar class="d-flex align-content-stretch justify-content-sm-between">

                    <div>
                        <b-button-group  class="mx-1">
                            <b-button variant="secondary" class="btn-icon text-muted-dark" @click="currPage = 1">
                                <i class="fa fa-fast-backward"></i>
                            </b-button>
                            <b-button variant="secondary" class="btn-icon text-muted-dark" @click="currPage = (currPage < 1 ? 1: currPage - 1 )">
                                <i class="fa fa-step-backward"></i>
                            </b-button>
                            <b-button variant="secondary" class="btn-icon text-muted-dark" @click="currPage = (currPage >= meta.last_page ? meta.last_page: currPage + 1)">
                                <i class="fa fa-step-forward"></i>
                            </b-button>
                            <b-button variant="secondary" class="btn-icon text-muted-dark" @click="currPage = ( currPage = meta.last_page )">
                                <i class="fa fa-fast-forward"></i>
                            </b-button>
                        </b-button-group>
                    </div>

                    <div class="meta-info p-1">
                        <p v-if="isBusy" key="busy_meta_message" class="font-italic"><i class="fa fa-spinner fa-spin text-muted"></i> Bezig met laden...</p>
                        <p v-else-if="meta.from && meta.to && meta.total" key="result_meta_message">Toont <span class="meta-value">{{ meta.from }}</span> - <span class="meta-value">{{ meta.to }}</span> van de in totaal <span class="meta-value">{{ meta.total }}</span> gevonden Personen.</p>
                        <p v-else key="error_meta_message">Probleem met het ophalen van de meta-data.</p>
                    </div>

                    <div>
                        <b-button-group  class="mx-1">
                            <b-button variant="outline-info"><i class="fa fa-question mr-2"></i>Help</b-button>
                        </b-button-group>

                        <b-button-group  class="mx-1">
                            <b-button variant="outline-primary"><i class="fa fa-print mr-2"></i>Print</b-button>
                            <b-button variant="outline-primary"><i class="fa fa-download mr-2"></i>Download</b-button>
                        </b-button-group>

                        <b-button-group  class="mx-1">
                            <b-button variant="success" href="/people/create"><i class="fa fa-plus mr-2"></i>Persoon Toevoegen</b-button>
                        </b-button-group>

                        <b-button-group class="btn-icon" v-if="!showSidebar">
                            <b-button variant="primary" @click="showSidebar = true">
                                <i class="fa fa-chevron-down"></i>
                            </b-button>
                        </b-button-group>
                    </div>


                </b-button-toolbar>

                <hr class="my-3" />


            </b-col>





        </b-form-row>

        <b-row>
            <b-col>

                <b-form-row class="d-flex flex-row flex-nowrap mb-2">

                    <b-col lg="6">
                        <div class="input-icon">
                            <b-form-input type="search" placeholder="Zoeken..."></b-form-input>
                            <div class="input-icon-addon">
                                <i class="fe fe-search"></i>
                            </div>
                        </div>
                    </b-col>

                    <b-col lg="3">
                        <b-form-select v-model="sort">
                            <template slot="first">
                                <option value="null" disabled>-- Sorteren op --</option>
                            </template>
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
                        </b-form-select>
                    </b-col>
                    <b-col>
                        <b-form-select v-model="sortDesc">
                            <template slot="first">
                                <option :value="null" disabled>-- Ordening --</option>
                            </template>
                            <option :value="false">Oplopend</option>
                            <option :value="true">Aflopend</option>
                        </b-form-select>
                    </b-col>
                    <b-col>
                        <b-form-select v-model="perPage">
                            <template slot="first">
                                <option value="null" disabled>-- Max per pag. --</option>
                            </template>
                            <option :value="5">5</option>
                            <option :value="10">10</option>
                            <option :value="15">15</option>
                            <option :value="20">20</option>
                            <option :value="25">25</option>
                            <option disabled>----</option>
                            <option :value="50">50</option>
                            <option :value="75">75</option>
                            <option :value="100">100</option>
                            <option disabled>----</option>
                            <option :value="200">200</option>
                            <option disabled>----</option>
                            <option :value="500">500</option>

                        </b-form-select>
                    </b-col>


                </b-form-row>

                <b-card no-body>
                    <b-table id="people_search_table"
                             ref="table"
                             :busy.sync="isBusy"
                             :items="personProvider"
                             :fields="fields"
                             class="card-table"
                             :per-page="perPage"
                             :currentPage="currPage"
                             :sort-by.sync="sortBy"
                             :sort-desc.sync="sortDesc" hover responsive>


                        <base-avatar slot="avatar" slot-scope="{ item }" v-bind="item.avatar" default-color="blue" />


                        <template slot="name" slot-scope="data">
                            <div>{{ data.item.name.full }}</div>
                            <div class="text-muted small">{{ data.item.name.formal }} </div>
                        </template>

                        <people-search-col-birth-date slot="birth_date" slot-scope="data" :row="data.item" />
                        <people-search-col-membership-status slot="membership_status" slot-scope="data" :row="data.item" />

                        <template slot="links" slot-scope="data">
                            <a class="icon" :href="'/people/'+data.item.id">
                                <i class="fe fe-more-vertical"></i>
                            </a>
                        </template>

                    </b-table>

                </b-card>

                <b-pagination :total-rows="meta.total"
                              :per-page="perPage"
                              :limit="paginationLimit"
                              v-model="currPage">
                </b-pagination>
            </b-col>




            <b-col lg="3" v-show="showSidebar">

                <card-flexible title="Kolommen" collapsed>
                 <column-select v-model="columns" />
                </card-flexible>

                <membership-status-filter v-model="filters.membership_status" @input="refreshTable()"></membership-status-filter>

<!--
                <b-card class="bg-yellow-lighter" no-body>
                    <div class="card-status bg-red"></div>
                    <h3 slot="header" class="card-title"><i class="fa fa-bug"> Request parameters</i></h3>

                    <pre>{{ params }}</pre>

                </b-card>

-->
                <b-button variant="link" block @click="showSidebar = false">
                    <i class="fa fa-chevron-up mr-2"></i> Zijbalk verbergen
                </b-button>

            </b-col>


        </b-row>

    </b-container>

</template>

<script>
    import PeopleSearchColBirthDate from "./col/birth-date";
    import PeopleSearchColMembershipStatus from "./col/membership-status";
    import MembershipStatusFilter from './filter/membership-status';
    import BaseAvatar from '../../BaseAvatar';

    export default {

        components: {
            PeopleSearchColMembershipStatus,
            PeopleSearchColBirthDate,
            BaseAvatar,
            MembershipStatusFilter
        },

        data() {
            return {
                isBusy: false,
                meta: {},
                columns:[
                    {
                        key:'avatar',
                        label: '',
                        name:'Avatar',
                        visible:true,
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
                        sortable:'name_first',
                        visible:true
                    },
                    {
                        key:'name.short',
                        label: 'Naam',
                        name:'Korte Naam',
                        sortable:'name_nickname',
                        visible:false
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
                        visible:true,
                    }
                ],
                filters:{
                    membership_status: null
                },
                sort:'id',
                sortDesc:false,
                perPage:10,
                currPage:1,
                paginationLimit:19,
                showSidebar:true,
            }
        },

        methods: {

            refreshTable() {
                this.$refs.table.refresh();
            },

            personProvider(ctx) {
                let promise = axios.get('/people/search', {
                    params: this.params
                });

                return promise.then(response => {
                    const items = response.data.data;
                    this.meta = response.data.meta;
                    return(items);
                }).catch(error => {
                    console.log(error);
                });
            }
        },

        computed: {

            sortBy: {
                get:function() {
                    const col = this.columns.find(el => el.sortable === this.sort);
                    console.log('sortByGetter', col);
                    return col ? col.key : this.sort;
                },
                set:function(newValue) {
                    console.log(newValue);
                    const col = this.columns.find(el => el.key === newValue);
                    console.log('sortBySetter', col, newValue);
                    if(col && col.key) { this.sort = col.sortable }
                },
            },

            params: function() {
                return {
                    sort:this.sort,
                    sort_order:this.sortDesc ? 'desc' : 'asc',
                    per_page:this.perPage,
                    page:this.currPage,
                    membership_status: this.filters.membership_status,
                };
            },


            fields: function() {
                let res = [];
                for(let i = 0; i < this.columns.length; i++) {
                    const column = this.columns[i];

                    let cls = column.class || [];

                    if(!column.visible) {
                        cls.push('d-none');
                    }

                    res.push({
                        key: column.key,
                        label: column.label,
                        class: cls,
                        sortable: !!column.sortable,
                });
                }
                return res;
            }
        }

    };

</script>

<style scoped>

    .meta-value {
        font-weight: 600;
        margin-left: 4px;
        margin-right: 4px;
    }

    .meta-info p {
        margin-bottom: 0px;
    }

</style>