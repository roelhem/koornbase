<template>

    <tabler-form-group v-bind="tablerFormGroupProps">
        <b-row class="gutters-xs">
            <div class="col-auto" v-for="color in colors">
                <tabler-color-input :key="`color-${color}`"
                                    :color="color"
                                    v-model="modelValue"
                                    :name="id"
                                    :id="id+'_'+color"
                                    :radio="!multiple"
                />
            </div>
        </b-row>
    </tabler-form-group>


</template>

<script>
    import formGroupMixin from "../../mixins/formGroupMixin";
    import TablerFormGroup from "../TablerFormGroup";
    import TablerColorInput from "../TablerColorInput";
    import { COLORS } from "../../constants/style";

    export default {
        name: "form-color-input",

        model: {
            prop: 'value',
            event: 'change'
        },

        props: {
            colors: {
                type:Array,
                default: function() {
                    return COLORS.slice(0);
                }
            },
            value: [Array,String],
            multiple: {
                type:Boolean,
                default:false
            },
        },

        computed: {
            modelValue: {
                get() {
                    if(this.multiple && !Array.isArray(this.value)) {
                        return [];
                    }
                    return this.value;
                },
                set(newValue) { this.$emit('change', newValue); }
            }
        },

        components: {
            TablerColorInput,
            TablerFormGroup,
        },

        mixins: [formGroupMixin],
    }
</script>

<style scoped>

</style>