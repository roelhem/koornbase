<template>
    <tr>
        <th><slot name="label">{{ label }}</slot></th>
        <td><slot></slot></td>
    </tr>
</template>

<script>
    export default {
        name: "detail-entry",

        props: {

            label:String,

        }
    }
</script>

<style scoped>

</style>