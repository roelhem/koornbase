export default {

    // PROPERTIES
    props: {

        /** Property that shows the form as a modal. */
        id: {
            type:[String,Number],
            default: function() {
                return this.$options.name;
            }
        },


        /** Property that shows the form as a modal. */
        modal: {
            type:Boolean,
            default:false
        },

    },

    // DATA: STORED VARIABLES
    data() {
        return {
            messages: [],
            values: this.getInitFormValues(),
        };
    },


    // FORM OPTIONS
    form: {
        title:null,
        actionType:'default',
        values:null,
    },

    // COMPUTED VARIABLES
    computed: {

        // FORM LAYOUT SETUP

        /** A Reference to the form layout component of this form. */
        formLayout() {
            return this.$refs.formLayout;
        },

        /** An object that you can bind to the formLayout with the `v-bind` property. */
        formLayoutProps() {
            let actionType = 'default';
            if(this.$options.form && this.$options.form.actionType) {
                const opt = this.$options.form.actionType;
                actionType = typeof opt === 'function' ? opt.call(this) : opt;
            }

            return {
                ref:'formLayout',
                modal:this.modal,
                messages:this.messages,
                title:this.formTitle,
                formId:this.formId,
                actionType:actionType,
                ...this.$attrs
            };
        },

        /** An object with listeners for the formLayout. You can set this with the `v-on` property. */
        formLayoutListeners() {
            return {
                'update:messages': value => this.messages = value,
                'submit':this.submitHandler,
                'reset':this.resetHandler,
                ...this.$listeners
            }
        },

        // FORM PROPERTIES

        /** The title of the form. */
        formTitle() {
            if(this.$options.form && this.$options.form.title) {
                const title = this.$options.form.title;
                return typeof title === 'function' ? title.call(this) : title;
            }
            return undefined;
        },

        /** The base ID of the form. */
        formId() {
            return this.id;
        },


    },

    // METHODS
    methods: {

        /** Event Handlers. */
        submitHandler() { this.submit(); },
        resetHandler() { this.reset(); },


        /** Method that handles the submit logic. */
        submit() {

        },

        /** Method that handles the reset logic. */
        reset() {

        },


        /** Adds a message to the message array. */
        addMessage(message) { this.messages.push(message); },

        /** Show/Hide methods for when the form is displayed as a modal. */
        show() { this.formLayout.show(); },
        hide() { this.formLayout.hide(); },


        // CONVENIENCE METHODS


        /** Returns an object with the initial values of the form. */
        getInitFormValues() {
            if(this.$options.form && this.$options.form.values) {
                const values = this.$options.form.values;
                if(typeof values === 'function') {
                    return values.call(this);
                }
            }
            return {};
        },

        /** Returns a string that can be used as an ID for a field in this from */
        getFieldId(fieldName) { return this.formId + '_' + fieldName; }
    }

}