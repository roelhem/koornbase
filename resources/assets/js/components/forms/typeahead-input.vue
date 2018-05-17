<template>


    <div>

        <input type="text"
               class="form-control"
               :value="value"
               :name="name"
               :disabled="disabled"
               @input="onInput"
               @focus="showDropdown = true"
               @blur="showDropdown = false" />

    </div>

</template>

<script>
    export default {
        name: "typeahead-input",

        props:{
            value:String,
            name:String,
            disabled:{
                type:Boolean,
                default:false
            }
        },

        methods:{
            onInput( event ) {
                this.$emit('input', event.target.value );
            },
        }
    }
</script>

<style scoped>



</style>