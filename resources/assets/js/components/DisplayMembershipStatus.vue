<template>

    <span>
        <span class="status-icon" :class="status | membershipStatusColor"></span>
        <data-display v-if="title" title="Lid-status titel">{{ title }}</data-display>
        <data-display v-else title="Lid-status">{{ status | membershipStatusName }}</data-display>
        <span v-if="since" class="small text-muted">
            (sinds
            <data-display title="Lid-status"
                          class="text-muted-dark"
            >{{ since | date(dateSize) }}</data-display>
            )
        </span>
    </span>

</template>

<script>
    import DataDisplay from "./displays/data-display";
    import displayFilters from '../filters/display';

    export default {
        components: {DataDisplay},
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