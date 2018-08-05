<template>

    <b-modal ref="modal" v-bind="bModalProps" v-on="bModalListeners">
        <template slot="modal-header-close">
            <span></span>
        </template>

        <template v-if="withLoader">
            <tabler-dimmer :active="busy">
                <slot />
            </tabler-dimmer>
        </template>
        <template v-else>
            <slot />
        </template>

        <template slot="modal-footer"><slot name="footer" /></template>

    </b-modal>

</template>

<script>
    import TablerDimmer from "./TablerDimmer";

    export default {
        name: "tabler-modal",

        model: {
            prop: 'visible',
            event: 'change'
        },

        props: {
            id:String,

            title:String,
            titleTag:{
                type:String,
                default:'h4'
            },

            size:String,

            visible:Boolean,

            busy:Boolean,
            withLoader:{
                type:Boolean,
                default:false
            },

            lazy:Boolean,

            okTitle:{
                type:String,
                default:"OK"
            },

            cancelTitle:{
                type:String,
                default:"Annuleren"
            },

            // Default button options
            okOnly:Boolean,
            okDisabled:Boolean,
            cancelDisabled:Boolean,
            // Styling options
            centered:Boolean,
            // hide different parts
            hideHeader:Boolean,
            hideFooter:Boolean,
            hideHeaderClose:Boolean,
            hideBackdrop:Boolean,
            // stop behavior
            noFade:Boolean,
            noCloseOnBackdrop:Boolean,
            noCloseOnEsc:Boolean,
            noEnforceFocus:Boolean
        },

        computed: {

            bModalProps() {
                return {
                    id: this.id,
                    title: this.title,
                    titleTag: this.titleTag,

                    size:this.size,

                    visible:this.visible,

                    busy:this.busy,

                    lazy:this.lazy,

                    okTitle:this.okTitle,
                    cancelTitle:this.cancelTitle,

                    okOnly:this.okOnly,
                    okDisabled:this.okDisabled,
                    cancelDisabled:this.cancelDisabled,
                    centered:this.centered,
                    hideHeader:this.hideHeader,
                    hideFooter:this.hideFooter,
                    hideHeaderClose:this.hideHeaderClose,
                    hideBackdrop:this.hideBackdrop,
                    noFade:this.noFade,
                    noCloseOnBackdrop:this.noCloseOnBackdrop,
                    noCloseOnEsc:this.noCloseOnEsc,
                    noEnforceFocus:this.noEnforceFocus
                }
            },

            bModalListeners() {
                return {
                    change: args => this.$emit('change', args),
                    show:   args => this.$emit('show', args),
                    shown:  args => this.$emit('shown', args),
                    hide:   args => this.$emit('hide', args),
                    hidden: args => this.$emit('hidden', args),
                    ok:     args => this.$emit('ok', args),
                    cancel: args => this.$emit('cancel', args),
                };
            }

        },

        methods: {
            show() {
                this.$refs.modal.show();
            },
            hide() {
                this.$refs.modal.hide();
            }
        },

        components: {
            TablerDimmer
        }

    }
</script>

<style scoped>

</style>