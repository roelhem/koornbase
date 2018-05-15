<template>

</template>

<script>
    export default {
        name: "people-lidstatus-filter",
        prop:['active', 'value', 'at'],
        data:function() {
            return {
                isActive: this.active,
                isOutsider: true,
                isNovice: true,
                isMember: true,
                isFormerMember: true
            }
        },
        methods: {
        }
    }
</script>

<style scoped>

</style>