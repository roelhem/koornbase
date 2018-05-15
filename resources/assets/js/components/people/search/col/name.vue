<template>

    <td>
        <div>{{ row.name.full }}</div>
        <div class="text-muted small">
            {{ row.name.formal }}
        </div>
    </td>

</template>

<script>
    export default {
        name: "people-search-col-name",
        props: {
            row:Object
        },
    }
</script>

<style scoped>

</style>