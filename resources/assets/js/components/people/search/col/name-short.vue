<template>

    <td>{{ row.name.short }}</td>

</template>

<script>
    export default {
        name: "people-search-col-name-short",
        props: {
            row:Object
        },
    }
</script>

<style scoped>

</style>