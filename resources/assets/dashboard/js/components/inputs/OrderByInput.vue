<template>
    <b-input-group>
        <b-form-select :options="fieldOptions" v-model="field">
            <slot></slot>
        </b-form-select>
        <b-input-group-append>
            <order-by-direction-input v-model="direction" />
        </b-input-group-append>
    </b-input-group>
</template>

<script>
    import OrderByDirectionInput from "./OrderByDirectionInput";
    import OrderBy from "../../utils/OrderBy";

    export default {
        name: "order-by-input",

        model:{
            prop:'value',
            event:'input'
        },

        props: {

            value: {
                type:[String,Object],
            },

            options: {
                type:Array,
                default() { return []; }
            },

            withoutNullOption:{
                type:Boolean,
                default:false
            },

            nullOptionText: {
                type:String,
                default:'-- Sorteren op --',
            },

        },

        computed: {

            nullOption() {
                return {
                    value:null, text:this.nullOptionText, disabled:true
                };
            },

            fieldOptions() {
                if(this.withoutNullOption) {
                    return this.options;
                } else {
                    return [this.nullOption].concat(this.options);
                }
            },

            orderBy: {
                get() { return OrderBy.parse(this.value); },
                set(newValue) { this.$emit('input', OrderBy.parse(newValue)); }
            },

            field: {
                get() { return this.orderBy ? this.orderBy.field || null : null; },
                set(newValue) {
                    this.orderBy = new OrderBy(newValue, OrderBy.ASC);
                }
            },

            direction: {
                get() { return this.orderBy ? this.orderBy.dir || OrderBy.ASC : OrderBy.ASC; },
                set(newValue) { this.orderBy = new OrderBy(this.field, newValue); }
            },

        },

        components: {OrderByDirectionInput},
    }
</script>

<style scoped>

</style>