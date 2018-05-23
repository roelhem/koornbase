/*
 +--------------------------------------------------------------------------------------------------
 | STRUCTUUR VAN EEN COLUMN-OBJECT
 +--------------------------------------------------------------------------------------------------
 | ! key:         PrimaryKey van de column binnen een searchTable.
 | ! label:       De tekst die boven een kolom staat.
 | ! name:        De naam van de kolom op andere plekken.
 |
 | ! visible:     Of de kolom getoond moet worden in de tabel.
 |
 | ? sortable:    Als ingesteld, de naam van de sort-methode die overeenkomt met deze column.
 |
 | ~ formatter:
 | ~ isRowHeader:
 |
 | ~ class:
 | ~ tdClass:
 | ~ thClass:
 | ~ thStyle;
 | ~ variant:
 | ~ tdAttr:
 |
 */

import axios from 'axios';

export default {

    data: function() {
        return {
            page:1,
            perPage:10,
            sort:null,
            sortOrder:'asc',

            isLoading:false,
            hasError:false,
            meta:{},

            columns:[],
        }
    },

    computed: {

        params: function() {
            return this.getDefaultParams()
        },

        endpoint:function() {
            return '';
        },

        sortByColumn: {
            get() {
                const column = this.columns.find(el => el.sortable === this.sort);
                if(column) {
                    return column.key;
                } else {
                    return this.sort;
                }
            },
            set(newValue) {
                const column = this.columns.find(el => el.key === newValue);
                if(column && column.sortable) {
                    this.sort = column.sortable;
                }
            },
        },

        sortDesc: {
            get() {
                return this.sortOrder === 'desc';
            },
            set(value) {
                this.sortOrder = value ? 'desc' : 'asc';
            }
        },

        tableFields: function() {
            return this.columns.map(col => this.columnToField(col));
        }
    },

    methods: {

        itemsProvider(ctx) {
            let promise = axios.get(this.endpoint, {
                params: this.params,
            });

            return promise.then(response => {
                const items = response.data.data;
                this.meta = response.data.meta;

                this.hasError = false;

                return( items );
            }).catch(error => {

                console.log(error);

                this.hasError = true;
            });
        },

        getDefaultParams() {
            let res = {};

            res.per_page = this.perPage;
            res.page = this.page;

            if(this.sort) {
                res.sort = this.sort;
                res.sort_order = this.sortOrder;
            }

            return res;
        },

        columnToField(column) {
            let cls = Array.isArray(column.class) ? column.class : [column.class];
            if(!column.visible) {
                cls.push('d-none');
            }

            return {
                key:column.key,
                label:column.label,
                class: cls,
                sortable: !!column.sortable,
                formatter: column.formatter,
                isRowHeader: column.isRowHeader,
                tdClass: column.tdClass,
                thClass: column.thClass,
                thStyle: column.thStyle,
                variant: column.variant,
                tdAttr: column.tdAttr,
            };
        }
    }

}