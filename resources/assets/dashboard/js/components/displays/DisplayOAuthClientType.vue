<template>

    <base-tag :color="bgColor" rounded v-b-tooltip.hover.right.d500="shortDescription">
        <strong v-if="!this.revoked">{{ label }}</strong>
        <span v-else>[{{ label }}]</span>
    </base-tag>

</template>

<script>
    import BaseTag from "./BaseTag";
    import displayFilters from '../../utils/filters/display';

    const PERSONAL = 'PERSONAL';
    const PASSWORD = 'PASSWORD';
    const CREDENTIALS = 'CREDENTIALS';
    const AUTH_CODE = 'AUTH_CODE';

    export default {
        components: {BaseTag},
        name: "display-o-auth-client-type",

        props: {
            type:{
                type:String,
                required:true
            },

            large:{
                type:Boolean,
                default:false
            },

            revoked:{
                type:Boolean,
                default:false
            }
        },

        computed: {
            shortLabel() { return displayFilters.oAuthClientTypeShortLabel(this.type); },
            largeLabel() { return displayFilters.oAuthClientTypeLargeLabel(this.type); },
            shortDescription() { return displayFilters.oAuthClientTypeShortDescription(this.type); },


            label() {
                if(this.large) {
                    return this.largeLabel;
                }
                return this.shortLabel;
            },

            bgColor() {
                if(this.revoked) {
                    return undefined;
                }

                return displayFilters.oAuthClientTypeColor(this.type);
            }
        }
    }
</script>

<style scoped>

</style>