export default {


    props: {
        value:null,

        disabled:{
            type:Boolean,
            default:false,
        }
    },

    data() {
        return {
            inputValue:null,
            formActive:false,
        }
    },

    methods: {

        reloadValue() {
            this.inputValue = this.value;
        },

        activateForm() {
            if(!this.disabled) {
                this.reloadValue();
                this.formActive = true;
                this.$emit('active');
            }
        },

        cancelForm() {
            this.formActive = false;
            this.reloadValue();
            this.$emit('cancel');
        },

        submitForm() {
            this.$emit('submit', this.inputValue);
            this.formActive = false;
        }

    }



}