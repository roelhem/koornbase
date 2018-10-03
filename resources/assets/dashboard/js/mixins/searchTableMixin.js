import OrderBy from "../utils/OrderBy";

export default {


    // SEARCH TABLE OPTIONS
    searchTable: {
        /** An array of all the column definitions. */
        columns:[],

        /** The name of the property that stores the row data. */
        queryKey:'records',
        query: {},

        /** The default (or initial) values of the sorting parameters. */
        defaults: {
            page:1,
            perPage:10
        },

        /** Option for the `id` of the table. */
        tableId:null,
        tableClass:null,

        cardTable:true,


        paginationWidthLimit:11,

        // Options for language
        /** How to name a collection of records in the table. */
        recordsName:'records'
    },


    // DATA VARIABLES
    data() {
        const opts = this.$options.searchTable || {};
        const defaults = opts.defaults || {};

        let res = {
            page   :defaults.page    || 1,
            perPage:defaults.perPage || 10,
            orderBy:defaults.orderBy ? OrderBy.parse(defaults.orderBy) : null,

            columns:this.getInitColumns(),
        };

        const p = this.getQueryKey();
        res[p] = this.getDefaultResponse();

        return res;
    },


    // COMPUTED VARIABLES
    computed: {



        // SOME SIMPLE SHORTCUTS

        from()      { return this.pageInfo.startIndex; },
        to()        { return this.pageInfo.endIndex; },
        total()     { return this.results.totalCount || 0; },

        pageInfo() { return this.results.pageInfo || {startIndex:0,endIndex:0}; },
        edges() { return this.results.edges || [] },

        isLoading() { return this.query.loading; },

        /** Should return a list of objects that are ready to be shown in the table. */
        rows()      { return this.edges.map(edge => edge.node); },



        // OTHER HELPER PROPERTIES

        /** The query key as a computed variable. */
        queryKey() { return this.getQueryKey(); },

        /** The contents of the query. */
        results: {
            get() { return this[this.queryKey] || {}; },
            set(newValue) { this[this.queryKey] = newValue; }
        },

        /** The query as a computed variable */
        query() {
            return this.$apollo.queries[this.queryKey];
        },




        // VARIABLES TO CONTROL A `<b-table>` ELEMENT


        dataTableProps() {
            return {
                items:this.rows,
                busy:this.isLoading,
                orderBy:this.orderBy,
                noLocalSorting:true
            };
        },

        /** Returns the event listenters for the search table. Can be binded to a b-table with `v-on`. */
        dataTableListeners() {
            return {
                'update:orderBy':val => this.orderBy = val,
            };
        },


        // VARIABLES TO CONTROL A THE CONFIGURATION ELEMENTS AROUND THE TABLE

        // SORT-BY SELECTOR
        /** Returns a list of all the columns that can be sorted. */
        sortableFields() { return this.columns.filter(column => this.columnIsSortable(column)); },

        /** Returns a list of objects that represent options in a select input. */
        sortSelectOptions() { return this.columnsToSortSelectOptions(this.sortableFields); },

        /** Returns the props for the sort-by selector. */
        sortInputProps() {
            return {
                sort:this.orderBy ? this.orderBy.field : null,
                sortOrder:this.orderBy ? this.orderBy.dir : OrderBy.ASC,
                options:this.sortSelectOptions,
            }
        },

        /** Returns the listeners for the sort-by selector. */
        sortInputListeners() {
            return {

            }
        },

        // SEARCH HEADER
        /** Returns the props for the search header. Can be binded to a search-header with `v-bind`. */
        searchHeaderProps() {
            let recordsName = 'records';
            if(this.$options.searchTable && this.$options.searchTable.recordsName) {
                recordsName = this.$options.searchTable.recordsName;
            }

            return {
                isLoading:   this.isLoading,
                page:        this.page,
                perPage:     this.perPage,
                from:        this.from,
                to:          this.to,
                total:       this.total,
                recordsName: recordsName
            };
        },

        /** Returns the event listeners for the search header. Can be binded to a search-header with `v-on`. */
        searchHeaderListeners() {
            return {
                change:this.changePage
            }
        },


        // PAGINATOR
        /** Returns the props for the pagination object. Can be binded to a b-pagination with `v-bind`. */
        paginationProps() {
            let limit = 11;
            if(this.$options.searchTable && this.$options.searchTable.paginationWidthLimit) {
                limit = this.$options.searchTable.paginationWidthLimit;
            }

            return {
                totalRows: this.total,
                perPage: this.perPage,
                limit: limit,
                value: this.page,
            };
        },

        /** Returns the event listeners for the pagination object. Can be binded to a b-pagination with `v-on`. */
        paginationListeners() {
            return {
                change: this.changePage,
            }
        }

    },


    // METHODS
    methods: {

        // METHODS FOR NAVIGATING TROUGH PAGES
        /** Changes the page to the provided page number. */
        changePage(pageNumber) {
            this.page = pageNumber;
        },



        // METHODS FOR APOLLO
        getQueryKey() {
            if(this.$options.searchTable && this.$options.searchTable.queryKey) {
                return this.$options.searchTable.queryKey;
            } else {
                return 'records';
            }
        },

        getDefaultResponse() {
            return {
                from:0,
                to:0,
                total:0,
                data:[]
            };
        },



        // METHODS FOR PARSING AND USING COLUMNS

        /** Method that parses a object with column options and returns te parsed object. */
        parseColumn(column) {
            let res = Object.assign({}, column);


            if(typeof res.label !== 'string') { res.label = res.key; }
            if(typeof res.name  !== 'string')  { res.name  = res.label; }

            if(!('visible' in res)) {
                res.visible = false;
            } else if(typeof res.visible !== 'boolean') {
                res.visible = !!res.visible;
            }

            return res;
        },

        /** Parses an array of column option objects. */
        parseColumns(columns) {
            let res = [];

            for(let i = 0; i < columns.length; i++) {
                res[i] = this.parseColumn(columns[i]);
            }

            return res;
        },

        /** Parses and returns the columns property of the searchTable options. */
        getInitColumns() {
            if(this.$options.searchTable && this.$options.searchTable.columns) {
                return this.parseColumns(this.$options.searchTable.columns);
            }
            return [];
        },

        /** Determines if the provided column should be visible in the table. */
        columnVisibleInTable(column) {
            return !!column.visible;
        },

        /** Determines if you can sort at the provided table. */
        columnIsSortable(column) {
            return !!column.sortable;
        },

        /** Converts an column to a object that can in turn be converted to a select option in the b-form-select. */
        columnToSortSelectOption(column) {
            // find the text
            let text = null;
            if('sortName' in column && typeof column.sortName === 'string') {
                text = column.sortName;
            } else {
                text = column.name || column.label || column.key;
            }

            // find the value
            let value = null;
            if('sortField' in column && typeof column.sortField === 'string') {
                value = column.sortField;
            } else {
                value = column.key;
            }

            // find if the option is available
            let disabled = true;
            if(this.columnIsSortable(column)) {
                disabled = false;
            }

            // return the result
            return { text, value, disabled };
        },

        /** Converts an array of columns into an array of sortSelectOption object. */
        columnsToSortSelectOptions(columns) {
            let res = [];
            for(let i = 0; i < columns.length; i++) {
                res[i] = this.columnToSortSelectOption(columns[i]);
            }
            return res;
        }
    },


    // CREATED HOOK FOR REGISTERING THE APOLLO SMART QUERY
    created() {
        if(this.$options.searchTable && this.$options.searchTable.query) {
            const key = this.getQueryKey();
            let query = this.$options.searchTable.query;

            this.$apollo.addSmartQuery(key, query);
        } else {
            console.error('No query was found in the searchTable!');
        }
    }


}