<template>

    <td class="w-1">
        <a class="icon" :href="'/people/'+row.id">
            <i class="fe fe-more-vertical"></i>
        </a>
    </td>

</template>

<script>
    export default {
        name: "people-search-col-link",
        props: {
            row:Object
        }
    }
</script>

<style scoped>

</style>