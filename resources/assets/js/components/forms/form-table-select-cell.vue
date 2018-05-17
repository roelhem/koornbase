<template>

    <div class="text-center">
        <custom-control-input type="radio"
                              :value="value"
                              :name="globalName"
                              :checked="checked"
                              :disabled="disabled"
                              inline
                              @change="onChange">
            {{ label }}
        </custom-control-input>
        <input type="hidden" :name="name" :value="checked ? '1' : '0'">
    </div>

</template>

<script>
    import CustomControlInput from "./custom-control-input";

    export default {
        name: "form-table-select-cell",

        model:{
            prop:'checked',
            event:'change',
        },

        props: {
            name:String,
            globalName:{
                type:String,
                default:'__table_select'
            },
            checked:{
                type:Boolean,
                default:false,
            },
            disabled:{
                type:Boolean,
                default:false
            },
            label:String,
            formTableRowIndex:{
                type:Number
            },
            value:{
                default:function() {
                    return this.formTableRowIndex.toString();
                }
            },
        },

        methods:{
            onChange:function( newValue )
            {
                this.$emit('change', newValue, {
                    otherValues: false
                } );
            },
        },


        components: {CustomControlInput},
    }
</script>

<style scoped>

</style>