<template>

    <tabler-form-group v-bind="tablerFormGroupProps">
        <b-form-input :id="id"
                      :class="inputClass"
                      :type="type"
                      :placeholder="placeholder"
                      :required="required"
                      :disabled="disabled"
                      v-model="modelValue"
        ></b-form-input>

    </tabler-form-group>

</template>

<script>
    import TablerFormGroup from "../layouts/forms/TablerFormGroup";
    import ValidationFeedback from "../layouts/forms/ValidationFeedback";
    import formGroupMixin from "../../mixins/formGroupMixin";
    import withValidationMixin from "../../mixins/withValidationMixin";

    export default {
        components: {
            ValidationFeedback,
            TablerFormGroup
        },
        name: "form-simple-input",

        mixins:[formGroupMixin, withValidationMixin],

        model: {
            prop:'value',
            event:'input'
        },

        props: {
            value:String,
            disabled:Boolean,
            placeholder:String,
            type:{
                type:String,
                default:'text'
            }
        },

        computed: {

            modelValue: {
                get() { return this.value; },
                set( newValue ) { this.$emit('input', newValue); }
            },

            inputClass() {
                let res = [];
                if(this.isValid) { res.push('state-valid'); }
                if(this.isInvalid) { res.push('state-invalid'); }
                return res;
            },

        }
    }
</script>

<style scoped>

</style>