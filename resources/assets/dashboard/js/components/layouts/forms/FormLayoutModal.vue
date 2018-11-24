<template>
    <tabler-modal ref="modal"
                  :id="id"
                  :title="title"
                  lazy
                  :size="modalSize"
                  v-bind="$attrs"
                  v-on="$listeners"
    >

        <div>
            <tabler-alert v-for="(message, index) in messages"
                          :key="'message_'+index"
                          variant="danger"
                          icon="alert-triangle"
                          dismissible
                          @dismiss="$emit('dismiss-message', index)"
            >{{ message }}</tabler-alert>
        </div>

        <slot />

        <template slot="footer">
            <b-button :variant="cancelVariant" @click="cancelHandler">{{ cancelText }}</b-button>
            <b-button :variant="resetVariant" @click="resetHandler">{{ resetText }}</b-button>
            <b-button :variant="submitVariant" @click="submitHandler">{{ submitText }}</b-button>
        </template>
    </tabler-modal>
</template>

<script>
    import TablerModal from "../modals/TablerModal";
    import TablerAlert from "../alerts/TablerAlert";

    export default {
        components: {
            TablerAlert,
            TablerModal},
        name: "form-layout-modal",

        props: {
            title:String,
            messages: {
                type:Array,
                default:function() {
                    return [];
                }
            },
            id:String,

            cancelText: {
                type:String,
                default:"Annuleren"
            },
            cancelVariant: {
                type:String,
                default:"secondary"
            },
            submitText: {
                type:String,
                default:"Versturen"
            },
            submitVariant: {
                type:String,
                default:"primary"
            },
            resetText: {
                type:String,
                default:"Resetten"
            },
            resetVariant: {
                type:String,
                default:"secondary"
            },
            modalSize:{
                type:String,
            }
        },

        methods: {
            hide() {
                console.log('modal', this.$refs.modal);
                this.$refs.modal.hide();
            },
            show() {
                this.$refs.modal.show();
            },
            cancelHandler() {
                this.hide();
            },
            resetHandler() {
                this.$emit('reset');
            },
            submitHandler() {
                this.$emit('submit');
            }
        }
    }
</script>

<style scoped>

</style>