<template>

    <tabler-form-group v-bind="tablerFormGroupProps">
        <b-form-textarea :id="id"
                         v-model="modelValue"
                         :placeholder="placeholder"
                         :required="required"
                         :disabled="disabled"
                         :rows="rows"
                         :max-rows="maxRows"
                         :no-resize="noResize"
                         :readonly="readonly"
                         :plaintext="plaintext"
                         :size="size"
                         :state="validationState"
        >
        </b-form-textarea>
    </tabler-form-group>

</template>

<script>
    import TablerFormGroup from "../layouts/forms/TablerFormGroup";
    import formGroupMixin from "../../mixins/formGroupMixin";
    import withValidationMixin from "../../mixins/withValidationMixin";

    export default {
        name: "form-textarea-input",

        components: {
            TablerFormGroup
        },
        mixins:[formGroupMixin, withValidationMixin],

        model: {
            prop:'value',
            event:'input'
        },

        props: {
            value:String,
            disabled:Boolean,
            placeholder:String,

            rows:{
                type:Number,
                default:3,
            },
            maxRows:{
                type:Number,
                default:6
            },
            noResize:{
                type:Boolean,
                default:false,
            },
            readonly:{
                type:Boolean,
                default:false,
            },
            plaintext:{
                type:Boolean,
                default:false,
            },
            size:String
        },

        computed: {

            modelValue: {
                get() {
                    return this.value;
                },
                set(newValue) {
                    this.$emit('input', newValue);
                }
            },
        }
    }
</script>

<style scoped>

</style>