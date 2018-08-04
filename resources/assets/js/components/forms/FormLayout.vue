<template>

    <component ref="component" :is="layoutComponent" v-bind="layoutProps" v-on="layoutListeners">
        <slot />
    </component>

</template>

<script>
    import FormLayoutCard from "./FormLayoutCard";
    import FormLayoutModal from "./FormLayoutModal";

    export default {
        name: "form-layout",

        props: {
            title:String,
            formId:String,
            messages:{
                type:Array,
                default:function() {
                    return [];
                }
            },

            actionType: {
                type:String,
                default:'default',
                validate:function(val) {
                    return ['default','create','update','delete'].indexOf(val) !== -1;
                }
            },

            modal: {
                type:Boolean,
                default:false,
            },

            cancelText:String,
            cancelVariant:String,
            resetText:String,
            resetVariant:String,
            submitText:String,
            submitVariant:String
        },

        computed: {
            layoutComponent() {
                if(this.modal) {
                    return FormLayoutModal;
                }
                return FormLayoutCard;
            },

            theFormId() {
                if(this.formId) {
                    return this.formId;
                }
                return undefined;
            },

            defaultSubmitText() {
                switch(this.actionType) {
                    case 'create':
                        return 'Toevoegen';
                    case 'update':
                        return 'Bijwerken';
                    case 'delete':
                        return 'Verwijderen';
                    case 'default':
                    default:
                        return 'Versturen';
                }
            },

            defaultSubmitVariant() {
                switch(this.actionType) {
                    case 'create':
                        return 'success';
                    case 'update':
                        return 'primary';
                    case 'delete':
                        return 'danger';
                    default:
                        return undefined;
                }
            },

            layoutId() {
                const id = this.theFormId;

                if(!id) {
                    return undefined;
                }

                if(this.modal) {
                    return id + '_modal';
                }
                return id + '_card';
            },

            layoutProps() {
                return {
                    title: this.title,
                    messages: this.messages,
                    id: this.layoutId,
                    cancelText:this.cancelText,
                    cancelVariant:this.cancelVariant,
                    resetText:this.resetText,
                    resetVariant:this.resetVariant,
                    submitText:this.submitText || this.defaultSubmitText,
                    submitVariant:this.submitVariant || this.defaultSubmitVariant,
                };
            },

            layoutListeners() {
                return {
                    submit:this.submit,
                    reset:this.reset,
                    'dismiss-message':this.dismissMessage
                };
            }
        },

        methods: {
            submit() {
                this.$emit('submit');
            },
            reset() {
                this.$emit('reset');
            },
            dismissMessage(index) {
                let res = this.messages.slice();
                res.splice(index, 1);
                this.$emit('update:messages', res);
            },

            show() {
                if(this.modal) {
                    this.$refs.component.show();
                }
            },
            hide() {
                if(this.modal) {
                    this.$refs.component.hide();
                }
            }
        }
    }
</script>

<style scoped>

</style>