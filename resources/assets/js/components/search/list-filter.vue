<template>

    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <membership-status :value="0"></membership-status>
        </li>
        <li class="list-group-item">
            <membership-status :value="1"></membership-status>
        </li>
        <li class="list-group-item">
            <membership-status :value="2"></membership-status>
        </li>
        <li class="list-group-item">
            <membership-status :value="3"></membership-status>
        </li>
    </ul>

</template>

<script>
    export default {
        name: "list-filter"
    }
</script>

<style scoped>

</style>