<template>
    <user-avatar :image="avatar.image" :letters="avatar.letters" :placeholder="avatar.placeholder"></user-avatar>
</template>

<script>
    export default {
        name: "person-avatar",
        props: {
            avatar:Object
        }
    }
</script>

<style scoped>

</style>