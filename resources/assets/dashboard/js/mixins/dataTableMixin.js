import OrderBy from "../utils/OrderBy";


export default {


    data() {
        return {
        };
    },


    props: {
        fields:{
            type:Array,
            default() {
                return this.defaultVisibleColumnNames;
            }
        },

        noLocalSorting:{
            type:Boolean,
            default:false,
        },

        orderBy:{
            type:String|Object,
        }

    },



    computed: {

        /** A list of the columns defined for this table. */
        columns() {
            const res = {};
            const columnDefinitions = this.$options.columns || {};


            for(let prop in columnDefinitions) {
                const colDef = columnDefinitions[prop];

                res[prop] = Object.assign({
                    key:prop,
                    label:colDef.name || prop,
                    name:colDef.label || prop,
                    sortField: prop,
                    sortable: colDef.sortField !== undefined
                }, colDef);
            }

            return res;
        },

        defaultVisibleColumnNames() {
            const res = [];
            for(let prop in this.columns) {
                if(this.columns[prop].visible) {
                    res.push(prop);
                }
            }
            return res;
        },


        modelOrderBy: {
            get() {
                return OrderBy.parse(this.orderBy);
            },
            set(newValue) {
                const res = OrderBy.parse(newValue);
                this.$emit('update:orderBy', res);
            }
        },

        /** An array with the definitions of the columns, for the table. */
        tableFields() {
            const fields = this.fields || this.defaultVisibleColumnNames;
            return fields.map(field => this.getField(field));
        },

        tableSortBy() {
            if(this.modelOrderBy instanceof OrderBy) {
                const sortField = this.modelOrderBy.field;
                const column = this.getColumnBySortField(sortField);
                if(column && column.key) {
                    return column.key;
                }
            }
            return null;
        },

        tableSortDesc() {
            if(this.modelOrderBy instanceof OrderBy) {
                return this.modelOrderBy.desc;
            }
            return false;
        },

        /** The properties for the table. */
        tableProps() {
            return Object.assign({
                fields:this.tableFields,
                noLocalSorting:this.noLocalSorting,
                sortBy:this.tableSortBy,
                sortDesc:this.tableSortDesc,
            }, this.$attrs);
        },

        /** The listeners for the table. */
        tableListeners() {
            return Object.assign({
                'update:sortBy': this.handleUpdateSortBy,
                'update:sortDesc': this.handleUpdateSortDesc,
            }, this.$listeners);
        }

    },

    methods: {

        handleUpdateSortBy(val) {
            const sortField = val in this.columns ? this.columns[val].sortField : val;
            const direction = this.modelOrderBy ? this.modelOrderBy.dir : OrderBy.ASC;
            this.modelOrderBy = new OrderBy(sortField, direction);
        },

        handleUpdateSortDesc(val) {
            const sortField = this.modelOrderBy ? this.modelOrderBy.field : undefined;
            const direction = val ? OrderBy.DESC : OrderBy.ASC;
            this.modelOrderBy = new OrderBy(sortField, direction);
        },

        getField(name) {
            if(typeof name === 'string' && name in this.columns) {
                return this.columns[name];
            } else {
                return name;
            }
        },

        getColumnBySortField(sortField) {
            for(let prop in this.columns) {
                const column = this.columns[prop];
                if(column.sortable && column.sortField === sortField) {
                    return column;
                }
            }
            return null;
        },

    }


}