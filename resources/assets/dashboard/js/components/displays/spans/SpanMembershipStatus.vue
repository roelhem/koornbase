<template>

    <span>
        <span class="status-icon" :class="status | membershipStatusColor"></span>
        <base-field v-if="title" title="Lid-status titel">{{ title }}</base-field>
        <base-field v-else title="Lid-status">{{ status | membershipStatusName }}</base-field>
        <span v-if="since" class="small text-muted">
            (sinds
            <base-field title="Lid-status"
                          class="text-muted-dark"
            >{{ since | date(dateSize) }}</base-field>
            )
        </span>
    </span>

</template>

<script>
    import displayFilters from '../../../utils/filters/display';
    import BaseField from "../BaseField";

    export default {
        components: {
            BaseField
        },

        name: "display-membership-status",

        props: {
            status:String,
            title:String,
            since:String,

            dateSize:{
                type:String,
                default:'lg',
            }
        },

        filters: displayFilters,
    }
</script>

<style scoped>

</style>