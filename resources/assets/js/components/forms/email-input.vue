<template>

    <input type="text" class="form-control" :value="value" :name="name" @input="onInput" />

</template>

<script>
    export default {
        name: "email-input",

        props:{
            value:String,
            name:String,
        },

        methods:{
            onInput( event ) {
                this.$emit('input', event.target.value );
            }
        }
    }
</script>

<style scoped>

</style>