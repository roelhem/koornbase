
export const DEFAULT_MODEL_SELECT_PROPS = {
    searchLimit: 25,
    placeholder: 'Zoeken...',
    selectLabel: null,
    selectGroupLabel: null,
    selectedLabel: null,
    deselectLabel: 'Verwijderen',
    deselectGroupLabel: 'Verwijder alle',
    displayLimitText: count => `En nog ${count}...`,
    showLabels: true,
};

function getDefaultPropFunction(propName, fallback) {
    return function() {
        if(this.$options.modelSelect && propName in this.$options.modelSelect) {
            return this.$options.modelSelect[propName];
        }
        if(DEFAULT_MODEL_SELECT_PROPS && propName in DEFAULT_MODEL_SELECT_PROPS) {
            return DEFAULT_MODEL_SELECT_PROPS[propName];
        }
        return fallback;
    }
}

function getSimpleProps(props, type, fallback) {
    let res = {};
    for(let i = 0; i < props.length; i++) {
        const prop = props[i];
        res[prop] = {
            type:type,
            default:getDefaultPropFunction(prop, fallback),
        };
    }
    return res;
}



export default {


    model: {
        prop:'value',
        event:'input'
    },



    props: {

        /** The ID of the select. */
        id: {
            type:String,
            default:function() {
                return this.$options.name;
            }
        },

        /** The value (most commonly an Object) of this modal that can be send to the database. */
        value:null,

        disabled:Boolean,
        multiple:Boolean,
        max:Number,

        size: {
            type:String,
            default:'md',
        },

        searchLimit: {
            type:Number,
            default: getDefaultPropFunction('searchLimit'),
        },
        limitOptions: {
            type:Boolean,
            default: getDefaultPropFunction('limitOptions',true),
        },

        ...getSimpleProps(['valueKey','trackBy'], String, 'id'),

        ...getSimpleProps(['label','groupValues','groupLabel','name'], String),

        // SOME PREFERENCES
        ...getSimpleProps(['placeholder','tagPlaceholder'], String),
        ...getSimpleProps(['selectLabel','selectGroupLabel','selectedLabel','deselectLabel','deselectGroupLabel'], String),

        displayLimit: {
            type:Number,
            default: getDefaultPropFunction('displayLimit'),
        },
        displayLimitText: {
            default: getDefaultPropFunction('displayLimitText'),
        },
        customLabel:{
            default: getDefaultPropFunction('customLabel'),
        },
        blockKeys: {
            type:Array,
            default: getDefaultPropFunction('blockKeys'),
        },

        ...getSimpleProps(['openDirection', 'tagPosition'], String),
        ...getSimpleProps(['tabindex'], Number),

        ...getSimpleProps(['clearOnSelect','showLabels','showPointer','showNoResults','allowEmpty', 'closeOnSelect','searchable'], Boolean, true),
        ...getSimpleProps(['hideSelected', 'resetAfter','groupSelect','preserveSearch','preselectFirst','internalSearch'], Boolean, false),

        ...getSimpleProps(['maxHeight','optionHeight'], Number),

        noBorder: {
            type:Boolean,
            default:false,
        },
    },


    data() {
        let res =  {
            searchQuery:null
        };

        res[this.getQueryKey()] = this.getDefaultResponse();

        return res;
    },



    computed: {

        // HELPER PROPERTIES FOR THE QUERIES

        /** The query key as a computed variable. */
        queryKey() { return this.getQueryKey(); },

        /** The contents of the query. */
        results: {
            get() { return this[this.queryKey]; },
            set(newValue) { this[this.queryKey] = newValue; }
        },

        /** The query as a computed variable */
        query() {
            return this.$apollo.queries[this.queryKey];
        },

        isLoading() {
            return this.query.loading;
        },


        // COMPUTED VARIABLES FOR SETTING THE MULTISELECT COMPONENT

        selectOptions() {
            const edges =this.results.edges;
            if(edges) {
                return edges.map(edge => edge.node)
            }
            return [];
        },

        optionsLimit() {
            if(this.limitOptions) {
                return this.searchLimit;
            }
            return undefined;
        },

        multiselectClasses() {
            let res = [];

            res.push('tabler-multiselect');

            if(this.noBorder) {
                res.push('multiselect-no-border');
            }

            return res;
        },


        /** The props of the multiselect component. */
        multiselectProps() {
            return {
                id: this.id,
                options: this.selectOptions,
                value: this.value,
                multiple: this.multiple,
                trackBy: this.trackBy,
                label: this.label,
                searchable: this.searchable,
                clearOnSelect: this.clearOnSelect,
                hideSelected: this.hideSelected,
                placeholder: this.placeholder,
                allowEmpty: this.allowEmpty,
                resetAfter: this.resetAfter,
                closeOnSelect: this.closeOnSelect,
                customLabel: this.customLabel,
                // taggable
                tagPlaceholder:this.tagPlaceholder,
                tagPosition:this.tagPosition,
                max:this.max,
                optionsLimit:this.optionsLimit,
                groupValues:this.groupValues,
                groupLabel:this.groupLabel,
                groupSelect:this.groupSelect,
                blockKeys:this.blockKeys,
                internalSearch:this.internalSearch,
                preserveSearch:this.preserveSearch,
                preselectFirst:this.preselectFirst,
                name:this.name,
                selectLabel:this.selectLabel,
                selectGroupLabel:this.selectGroupLabel,
                selectedLabel:this.selectedLabel,
                deselectLabel:this.deselectLabel,
                deselectGroupLabel:this.deselectGroupLabel,
                showLabels:this.showLabels,
                limit:this.displayLimit,
                limitText:this.displayLimitText,
                loading:this.isLoading,
                disabled:this.disabled,
                maxHeight:this.maxHeight,
                openDirection:this.openDirection,
                showNoResults:this.showNoResults,
                tabindex:this.tabindex,
                showPointer:this.showPointer,
                optionHeight:this.optionHeight,
                class:this.multiselectClasses
            };
        },

        /** The event listeners of the multiselect component. */
        multiselectListeners() {
            return {
                input:this.inputHandler,
                select:this.selectHandler,
                remove:this.removeHandler,
                'search-change':this.searchChangeHandler,
                tag:this.tagHandler,
                open:this.openHandler,
                close:this.closeHandler
            }
        },



    },



    methods: {

        // EVENT HANDLERS

        inputHandler(value, id) {
            this.$emit('input', value, id);
        },

        selectHandler(selectedOption, id) {
            this.$emit('select', selectedOption, id);
        },

        removeHandler(removedOption, id) {
            this.$emit('remove', removedOption, id);
        },

        searchChangeHandler(searchQuery, id) {
            console.log(searchQuery);
            this.searchQuery = searchQuery;
            this.$emit('search-change', searchQuery, id);
        },

        tagHandler(searchQuery, id) {
            this.$emit('tag', searchQuery, id);
        },

        openHandler(id) {
            this.$emit('open', id);
        },

        closeHandler(value, id) {
            this.$emit('close', value, id);
        },



        // HELPER METHODS

        getOptionFromValue(value) {
            if(value === null || value === undefined) {
                return undefined;
            }

            return {
                [this.valueKey]: value,
                needsLoading: true
            };
        },


        getValueFromOption(option) {

            if(option === null || option === undefined) {
                return null;
            }

            if(typeof option === 'string' || typeof option === 'number') {
                return option;
            }

            if(this.valueKey in option) {
                return option[this.valueKey];
            }

            return option;
        },

        getSelectedValue() {
            if(this.multiple) {
                return this.selected.map(item => this.getValueFromOption(item));
            }
            return this.getValueFromOption(this.selected);
        },


        // HELPER METHODS FOR THE APOLLO QUERY

        getQueryKey() {
            if(this.$options.modelSelect && this.$options.modelSelect.queryKey) {
                return this.$options.modelSelect.queryKey;
            }
            return 'options';
        },

        getDefaultResponse() {
            return {
                total:0,
                data:[],
            };
        },

        getDefaultVariables() {
            return {
                limit:this.searchLimit,
                search:this.searchQuery,
            }
        },

    },


    // CREATED HOOK FOR REGISTERING THE APOLLO SMART QUERY
    created() {
        if(this.$options.modelSelect && this.$options.modelSelect.query) {

            // Initializing and registering the main smart query.
            const key = this.getQueryKey();

            const mainQuery = Object.assign({
                variables:function() { return this.getDefaultVariables(); }
            }, this.$options.modelSelect.query);

            this.$apollo.addSmartQuery(key, mainQuery);


            // The query that loads the initial values.
            console.log(this.values);



        } else {
            console.error('Can\'t find the query for the modelSelect ' + this.$options.name);
        }
    },


}