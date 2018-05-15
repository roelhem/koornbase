<template>

    <div class="container" @keyup.left="prevPage" @keyup.right="nextPage">
        <div class="row">
            <div class="col-lg-3">

                <card-flexible title="Kolommen" :collapsed="true">
                    <column-select v-model="columns"></column-select>
                </card-flexible>

                <card-filter title="Lidstatus filter" v-model="membershipStatusFilterActive">
                    <div class="card-body p-2 px-4">
                        <div class="custom-controls-stacked">
                            <f-custom-checkbox v-model="membershipStatusIsOutsider"><membership-status :value="0"></membership-status></f-custom-checkbox>
                            <f-custom-checkbox v-model="membershipStatusIsNovice"><membership-status :value="1"></membership-status></f-custom-checkbox>
                            <f-custom-checkbox v-model="membershipStatusIsMember"><membership-status :value="2"></membership-status></f-custom-checkbox>
                            <f-custom-checkbox v-model="membershipStatusIsFormerMember"><membership-status :value="3"></membership-status></f-custom-checkbox>
                        </div>
                    </div>
                </card-filter>

                <card-filter title="Groepen Filter" v-model="groupFilterActive">
                    <people-group-filter v-model="groupFilterValue"></people-group-filter>
                </card-filter>

                <card-flexible title="Debugging">
                    <pre>{{ getParams() }}</pre>
                </card-flexible>
            </div>

            <div class="col-lg-9">

                <div class="row">
                    <div class="col-lg-7">
                        <people-search-meta :meta="meta"></people-search-meta>
                    </div>

                    <div class="col-lg-3">
                        <f-simple-select :options="sortingOptions" placeholder="Sorteren op..." v-model="sort"></f-simple-select>
                    </div>

                    <div class="col-lg-2">
                        <f-simple-select :options="perPageOptions" placeholder="Per pagina..." v-model="perPage"></f-simple-select>
                    </div>
                </div>

                <people-search-result-table :results="results" :columns="columns" :is-loading="isLoading"></people-search-result-table>

                <people-search-pager :links="links" @change-link="changeSource"></people-search-pager>
            </div>
        </div>


    </div>

</template>

<script>
    import axios from 'axios';
    import CardColumnSelect from "../search/column-select";
    import PeopleLidstatusFilter from "./people-lidstatus-filter";

    export default {
        name: "people-search-page",
        props: ['src'],
        data: function() {
            return {
                source:this.src,
                results:[],
                meta:{},
                links:{},
                isLoading: true,
                membershipStatusFilterActive: this.active,
                membershipStatusIsOutsider: false,
                membershipStatusIsNovice: false,
                membershipStatusIsMember: false,
                membershipStatusIsFormerMember: false,
                sortingOptions: [
                    { value:'id', text:'ID' },
                    { value:'name_first', text:'Voornaam' },
                    { value:'name_last', text:'Achternaam' },
                    { value:'name_nickname', text:'Bijnaam' },
                    { value:'birth_date', text:'Geboortedatum' },
                    { value:'status', text:'Lidstatus' }
                ],
                groupFilterActive: false,
                groupFilterValue: [],
                sort: 'name_first',
                perPageOptions: [
                    { value:'5', text:'5' },
                    { value:'10', text:'10' },
                    { value:'15', text:'15' },
                    { value:'20', text:'20' },
                    { value:'50', text:'50' },
                    { value:'100', text:'100' }
                ],
                perPage: '10',
                columns: {
                    'avatar': {
                        name: 'Avatar',
                        label: '',
                        visible: true
                    },
                    'id': {
                        name: 'ID',
                        label: 'ID',
                        visible: false
                    },
                    'name': {
                        name: 'Naam',
                        label: 'Naam',
                        visible: true
                    },
                    'name_short': {
                        name: 'Korte naam',
                        label: 'Korte naam',
                        visible: false
                    },
                    'birth_date': {
                        name: 'Geboortedatum',
                        label: 'Geboortedatum',
                        visible: true
                    },
                    'groups': {
                        name: 'Groepen',
                        label: 'Groepen',
                        visible: true
                    },
                    'membership_status': {
                        name: 'Status lidmaatschap',
                        label: 'Status',
                        visible: true
                    },
                }
            }
        },
        watch: {
            sort: function() { this.reload(); },
            membershipStatusFilterActive: function() { this.reload(); },
            membershipStatusIsOutsider: function() { this.reload(); },
            membershipStatusIsNovice: function() { this.reload(); },
            membershipStatusIsMember: function() { this.reload(); },
            membershipStatusIsFormerMember: function() { this.reload(); },
            groupFilterActive: function() { this.reload(); },
            groupFilterValue: function() { this.reload(); },
            perPage: function() { this.reload(); }
        },
        methods: {
            changeSource(value) {
                this.source = value;
                this.reload();
            },
            getParams() {
                let res = {
                    per_page: this.perPage,
                };

                if(this.sort) {
                    res['sort'] = this.sort;
                }

                if(this.groupFilterActive) {
                    res['groups'] = this.groupFilterValue;
                }

                if(this.membershipStatusFilterActive) {
                    res['membership_status'] = [];
                    if(this.membershipStatusIsOutsider) { res['membership_status'].push(0); }
                    if(this.membershipStatusIsNovice) { res['membership_status'].push(1); }
                    if(this.membershipStatusIsMember) { res['membership_status'].push(2); }
                    if(this.membershipStatusIsFormerMember) { res['membership_status'].push(3); }
                }

                return res;

            },
            reload() {
                this.isLoading = true;
                axios.get(this.source, {params: this.getParams()}).then(response => {
                    this.results = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;

                    this.isLoading = false;
                }).catch(error => {
                    console.log(error);
                    this.isLoading = false;
                });
            },
            nextPage() {
                console.log('next');
                if (this.links.next) {
                    this.changeSource(this.links.next)
                }
            },
            prevPage() {
                console.log('prev');
                if (this.links.prev) {
                    this.changeSource(this.links.prev)
                }
            }
        },
        created() {
            this.reload();
        },
        components: {
            CardColumnSelect,
            'people-group-filter': require('./people-group-filter'),
            'people-search-result-table': require('./people-search-result-table'),
            'people-search-meta': require('./people-search-meta'),
            'people-search-pager': require('./people-search-pager'),
        },
    }
</script>

<style scoped>

</style>