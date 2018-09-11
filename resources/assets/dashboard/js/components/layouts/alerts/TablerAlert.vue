<template>

    <div :class="alertClass" v-if="alertVisible" role="alert">

        <button v-if="dismissible"
                type="button"
                class="close"
                @click="dismiss"
                title="Sluiten"></button>

        <base-icon :icon="icon" :from="iconFrom" aria-hidden="true" />

        <slot />

    </div>

</template>

<script>
    import BaseIcon from "../../displays/BaseIcon";

    export default {
        components: {BaseIcon},
        name: "tabler-alert",

        props: {
            variant:String,

            dismissible:{
                type:Boolean,
                default:false
            },

            dismissed:{
                type:Boolean,
                default:false
            },

            icon: [String,Array],
            iconFrom: {
                type: [String, Array],
                default: function() {
                    return ['fe','fa'];
                }
            }
        },

        computed: {
            alertClass() {
                let res = ['alert'];

                if(this.variant) {
                    res.push('alert-' + this.variant);
                }

                if(this.dismissible) {
                    res.push('alert-dismissible');
                }

                if(this.isIconAlert) {
                    res.push('alert-icon');
                }

                return res;
            },

            alertVisible() {
                return !this.dismissed;
            },

            isIconAlert() {
                return !!this.icon;
            }

        },

        methods: {
            dismiss() {
                this.$emit('dismiss');
                this.$emit('update:dismissed', true);
            }
        }
    }
</script>

<style scoped>

</style>