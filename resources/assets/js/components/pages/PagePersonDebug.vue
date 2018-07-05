<template>

    <pre>{{ person }}</pre>

</template>

<script>
    export default {
        name: "page-person-debug",

        props: {
            person: {
                type:Object,
                default: function() {
                    return {};
                }
            }
        }
    }
</script>

<style scoped>

</style>