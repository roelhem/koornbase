export default {


    props: {
        value:null
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
            this.reloadValue();
            this.formActive = true;
            this.$emit('active');
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