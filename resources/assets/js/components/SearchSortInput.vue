<template>
    <b-input-group>
        <b-form-select :options="options" v-model="sortValue">
            <slot></slot>
        </b-form-select>
        <b-input-group-append>
            <search-sort-order-input v-model="sortOrderValue"></search-sort-order-input>
        </b-input-group-append>
    </b-input-group>
</template>

<script>
    import SearchSortOrderInput from "./SearchSortOrderInput";

    export default {
        components: {SearchSortOrderInput},
        name: "search-sort-input",

        model:{
            prop:'sort',
            event:'input',
        },

        props: {
            sort:String,

            sortOrder:{
                type:String,
                default:'asc',
                validate:function (val) {
                    return ['asc','desc'].indexOf(val) !== -1;
                }
            },

            options:{
                type:Array,
                default:function() {
                    return [];
                }
            }
        },

        computed: {

            sortValue:{
                get() { return this.sort; },
                set(newValue) {
                    if(newValue !== this.sort) {
                        this.sortOrderValue = 'asc';
                    }
                    this.$emit('input', newValue);
                }
            },

            sortOrderValue:{
                get() { return this.sortOrder; },
                set(newValue) { this.$emit('update:sortOrder', newValue); }
            }
        }
    }
</script>

<style scoped>

</style>