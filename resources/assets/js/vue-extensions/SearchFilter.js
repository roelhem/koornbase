export default Vue.extend({

    props: {
        value: {
            type: Object,
            default: function() {
                return {};
            },
        },
    },

    computed: {
        params:{
            get:function() {
                return this.value;
            },
            set:function(newValue) {
                this.$emit('input', newValue);
            }
        }
    }


});