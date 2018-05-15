export default Vue.extend({

    props: {
        value: {
            type: Array,
            default: function() {
                return [];
            },
        },
        params: {
            type: Object,
            default: function() {
                return {};
            },
        },
    },

    model: {
        prop: 'value',
        event: 'change'
    },

    methods: {

        changeValue( newValue ) {
            this.$emit( 'change', newValue );
        },

        hasVal( val ) {
            return this.value.indexOf( val ) >= 0;
        },

        addVal( val ) {
            if(!this.hasVal( val )) {
                this.changeValue(this.value.slice().push( val ));
            }
        },

        removeVal( val ) {
            let index = this.value.indexOf( val );
            if(index >= 0) {
                this.changeValue(this.value.slice().splice(index, 1));
            }
        },

        setVal( val, include ) {
            if(include) {
                this.addVal( val );
            } else {
                this.removeVal( val );
            }
        },

        toggleVal( val ) {
            let index = this.value.indexOf( val );
            if(index >= 0) {
                this.changeValue(this.value.slice().splice(index, 1));
            } else {
                this.changeValue(this.value.slice().push( val ));
            }
        }

    }

});