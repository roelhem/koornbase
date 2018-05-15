<template>

    <div class="container">

        <div class="row">

            <!-- The search configuration cards column. -->
            <div class="col-lg-3">

                <card-flexible title="Kolommen" :collapsed="true">
                    <column-select v-model="columns"></column-select>
                </card-flexible>


            </div>

            <!-- The main column -->
            <div class="col-lg-9">

                <div class="p-2">
                    <p v-if="loading" class="font-italic"><i class="fa fa-spinner fa-spin text-muted"></i> Laden van {{ recordName }}...</p>
                    <p v-else-if="meta.from && meta.to && meta.total">Records <span class="meta-value">{{ meta.from }}</span> - <span class="meta-value">{{ meta.to }}</span> van de in totaal <span class="meta-value">{{ meta.total }}</span> gevonden {{ recordName }}.</p>
                    <p v-else>Probleem met het ophalen van de meta-data.</p>
                </div>

                <card-result-table :columns="columns" :rows="rows"></card-result-table>

                <search-pager :meta="meta" @change="changePage"></search-pager>

            </div>

        </div>

    </div>

</template>

<script>
    import axios from "axios";
    import CardResultTable from "../cards/card-result-table";
    import SearchPager from "./pager";
    import SearchFilters from "./filters";

    export default {
        name: "search-page",
        props: {
            src: {
                type: String,
                default: 'search',
            },
            cols: {
                type: Array,
                default: function() {
                    return [];
                },
            },
            filters: {
                type: Array,
                default: function() {
                    return [];
                },
            },
            recordName: {
                type: String,
                default: 'records',
            }
        },
        data: function() {
            return {
                columns: this.cols,
                rows: [],
                meta: {},
                loading: false,
                page: 1,
                per_page: 10,
            };
        },
        methods: {

            changePage: function(page) {
                if(page !== null) {
                    this.page = page;
                    this.load();
                }
            },

            getParams: function() {
                let res = {
                    'page': this.page,
                    'per_page': this.per_page,
                };

                return res;
            },

            load: function() {
                // Updates the loading parameter
                this.loading = true;

                // Make a axios request
                axios.get(this.src, {
                    params: this.getParams()
                }).then(response => {

                    this.rows = response.data.data;
                    this.meta = response.data.meta;

                    this.loading = false;

                }).catch(error => {
                    console.log(error);

                    this.loading = false;
                });
            }
        },
        created() {
            this.load();
        },
        components: {
            SearchFilters,
            CardResultTable,
            SearchPager
        },
    }
</script>

<style scoped>

    .meta-value {
        font-weight: 600;
        margin-left: 4px;
        margin-right: 4px;
    }

</style>