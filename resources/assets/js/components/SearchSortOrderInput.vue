<template>
    <b-form-radio-group v-model="sortOrder" buttons>
        <b-form-radio value="asc" class="btn-icon">
            <base-icon :icon="{fe:'arrow-down', fa:'sort-amount-asc'}" :from="iconsFrom" />
        </b-form-radio>
        <b-form-radio value="desc" class="btn-icon">
            <base-icon :icon="{fe:'arrow-up', fa:'sort-amount-desc'}" :from="iconsFrom" />
        </b-form-radio>
    </b-form-radio-group>
</template>

<script>
    import baseIcon from './BaseIcon';

    export default {
        name: "search-sort-order-input",

        props: {

            value: {
                type:String,
                default:'asc',
            },

            iconsFrom: {
                type:[String, Array],
                default:function() {
                    return ['fe','fa'];
                }
            }

        },

        computed: {
            sortOrder: {
                get() { return this.value; },
                set(newValue) { this.$emit('input', newValue ); }
            }
        },

        components:{baseIcon}
    }
</script>

<style scoped>

</style>