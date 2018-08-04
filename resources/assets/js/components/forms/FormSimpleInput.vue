<template>

    <tabler-form-group :id="formGroupId"
                       :label="label"
                       :label-for="id"
                       :required="required"
    >
        <b-form-input :id="id"
                      :class="inputClass"
                      :type="inputType"
                      :placeholder="placeholder"
                      :required="required"
                      :disabled="disabled"
                      v-model="modelValue"
        ></b-form-input>

        <template slot="invalid-feedback">
            <div>
                <template v-for="(rule, key) in violatedRules">
                    <div v-if="$slots[key]"><slot :name="key" v-bind="rule">{{ key }}</slot></div>
                    <validation-feedback v-else :ruleKey="key" :rule="rule" />
                </template>
            </div>
        </template>

    </tabler-form-group>

</template>

<script>
    import TablerFormGroup from "../TablerFormGroup";
    import ValidationFeedback from "./ValidationFeedback";

    export default {
        components: {
            ValidationFeedback,
            TablerFormGroup},
        name: "form-simple-input",

        model: {
            prop:'value',
            event:'input'
        },

        props: {
            value:String,
            label:String,
            id:String,
            required:Boolean,
            disabled:Boolean,
            placeholder:String,
            validation:Object
        },

        computed: {

            formGroupId() {
                if(this.id) {
                    return this.id + '_fieldset';
                }
                return undefined;
            },

            modelValue: {
                get() { return this.value; },
                set( newValue ) { this.$emit('input', newValue); }
            },

            inputType() {
                return 'text';
            },

            inputClass() {
                let res = [];

                if(this.validation) {
                    if(this.validation.$dirty) {
                        if(this.validation.$invalid) {
                            res.push('state-invalid');
                        } else {
                            res.push('state-valid');
                        }
                    }
                }

                return res;
            },

            validationRules() {
                if(this.validation) {
                    if(this.validation.$params) {
                        return this.validation.$params;
                    }
                }
                return {};
            },

            violatedRules() {
                let res = {};

                if(!this.validation || !this.validation.$dirty) {
                    return res;
                }


                for(let key in this.validationRules) {
                    if(this.validationRules.hasOwnProperty(key) && this.validation.hasOwnProperty(key) && !this.validation[key]) {
                        res[key] = this.validationRules[key];
                    }
                }

                return res;
            }

        }
    }
</script>

<style scoped>

</style>