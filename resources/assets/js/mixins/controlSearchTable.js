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
            hasError:false,

            columns:[],
        }
    },

    computed: {

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
            return this.columns.filter(col => col.visible);
        }
    },

}