<template>
    <b-dropdown-item v-bind="$attrs" v-on="$listeners" v-b-tooltip.hover.left="shortDescription">
        <base-status-icon :color="type | oAuthClientTypeColor" />
        {{ type | oAuthClientTypeLargeLabel }}
    </b-dropdown-item>
</template>

<script>

    import displayFilters from '../../utils/filters/display';
    import BaseStatusIcon from "./BaseStatusIcon";

    export default {
        components: {BaseStatusIcon},
        name: "o-auth-client-type-dropdown-item",

        filters:displayFilters,

        props:{
            type:{
                type:String,
                required:true
            }
        },

        computed: {
            shortDescription() { return displayFilters.oAuthClientTypeShortDescription(this.type); },
        }
    }
</script>

<style scoped>

</style>