<template>
    <b-input-group>
        <b-form-select :options="sortOptions" v-model="sortValue">
            <slot></slot>
        </b-form-select>
        <b-input-group-append>
            <search-sort-order-input v-model="sortOrderValue"></search-sort-order-input>
        </b-input-group-append>
    </b-input-group>
</template>

<script>
    import SearchSortOrderInput from "./SearchSortOrderInput";

    const ASC = 'ASC';
    const DESC = 'DESC';

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
                default: ASC,
                validate:function (val) {
                    return [ASC,DESC].indexOf(val) !== -1;
                }
            },

            options:{
                type:Array,
                default:function() {
                    return [];
                }
            },

            withoutNullOption:{
                type:Boolean,
                default:false
            },

            nullOptionText: {
                type:String,
                default:'-- Sorteren op --',
            }
        },

        computed: {

            sortOptions() {
                if(this.withoutNullOption) {
                    return this.options;
                } else {
                    return [{
                        value:null, text:this.nullOptionText, disabled:true
                    }].concat(this.options);
                }
            },

            sortValue:{
                get() { return this.sort; },
                set(newValue) {
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