<template>
    <b-form-radio-group v-model="sortOrder" buttons>
        <b-form-radio value="ASC" class="btn-icon">
            <base-icon :icon="{fe:'arrow-down', fa:'sort-amount-asc'}" :from="iconsFrom" />
        </b-form-radio>
        <b-form-radio value="DESC" class="btn-icon">
            <base-icon :icon="{fe:'arrow-up', fa:'sort-amount-desc'}" :from="iconsFrom" />
        </b-form-radio>
    </b-form-radio-group>
</template>

<script>
    import baseIcon from '../displays/BaseIcon';
    import OrderBy from '../../utils/OrderBy';

    export default {
        name: "order-by-direction-input",

        model:{
            prop:'value',
            event:'input'
        },

        props: {

            value: {
                type:String,
                default:OrderBy.ASC,
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
                get() {
                    if(this.value === OrderBy.DESC || this.value === false) {
                        return OrderBy.DESC;
                    } else {
                        return OrderBy.ASC;
                    }
                },
                set(newValue) {
                    if(newValue === OrderBy.DESC || newValue === false) {
                        this.$emit('input', OrderBy.DESC );
                    } else {
                        this.$emit('input', OrderBy.ASC );
                    }
                }
            }
        },

        components:{baseIcon}
    }
</script>

<style scoped>

</style>