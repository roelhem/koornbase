<template>


    <b-form-group v-bind="bFormGroupProps">
        <template slot="label">
            <slot name="label">{{ this.label }}</slot>
            <span v-b-tooltip.hover.right.d750 v-if="required" class="form-required" :title="requiredText">*</span>
        </template>
        <slot />
        <template slot="invalid-feedback"><slot name="invalid-feedback" /></template>
    </b-form-group>

</template>

<script>
    export default {
        name: "tabler-form-group",

        props: {
            id:String,
            state:[Boolean,String],
            horizontal:Boolean,
            labelCols:[Number,String],
            breakpoint:String,
            labelTextAlign:String,
            label:String,
            labelFor:String,
            labelSize:String,
            labelSrOnly:Boolean,
            labelClass:[String,Array],
            description:String,
            invalidFeedback:String,
            feedback:String,
            validFeedback:String,
            validated:Boolean,
            required:{
                type:Boolean,
                default:false
            },
            requiredText:{
                type:String,
                default:"Dit is een verplicht veld"
            }
        },

        computed: {

            defaultId: function() {
                return this.labelFor + '_fieldset';
            },

            fullLabelClass: function() {
                if(typeof this.labelClass === 'string') {
                    return this.labelClass+' form-label';
                } else if(this.labelClass) {
                    return this.labelClass.concat(['form-label']);
                } else {
                    return 'form-label';
                }
            },

            bFormGroupProps: function() {
                return {
                    id: this.id || this.defaultId,
                    state: this.state,
                    horizontal: this.horizontal,
                    labelCols: this.labelCols,
                    breakpoint: this.breakpoint,
                    labelTextAlign: this.labelTextAlign,
                    labelFor: this.labelFor,
                    labelSize: this.labelSize,
                    labelSrOnly: this.labelSrOnly,
                    labelClass: this.fullLabelClass,
                    description: this.description,
                    invalidFeedback: this.invalidFeedback,
                    feedback: this.feedback,
                    validFeedback: this.validFeedback,
                    validated: this.validated
                }
            }
        }
    }
</script>

<style scoped>

</style>