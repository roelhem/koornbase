<template>

    <td>
        <div>
            <membership-status :value="row.membership.status"></membership-status>
        </div>
        <div v-if="row.membership.since" class="text-muted small">
            {{ row.membership.since | moment('dd D MMMM Y') }}
        </div>
    </td>


</template>

<script>
    export default {
        name: "people-search-col-membership-status",
        props: {
            row:Object
        },
    }
</script>

<style scoped>

</style>