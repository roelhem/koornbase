<template>

    <td class="w-1">
        <person-avatar :avatar="row.avatar"></person-avatar>
    </td>

</template>

<script>
    import PersonAvatar from "../../../displays/person-avatar";

    export default {
        components: {PersonAvatar},
        name: "people-search-col-avatar",
        props: {
            row:Object
        }
    }
</script>

<style scoped>

</style>