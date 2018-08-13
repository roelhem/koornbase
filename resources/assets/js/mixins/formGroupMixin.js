export const FORM_GROUP_ID_SUFFIX = '__fieldset';


const props = {

    id:String,
    label:String,

    required:{
        type:Boolean,
        default:false,
    },

    // Text settings
    description:String,
    requiredText:String,


    // Validation
    validation:Object,


    // Display settings
    horizontal:Boolean,
    labelCols:[Number,String],
    breakpoint:String,
    labelTextAlign:String,
    labelSize:String,
    labelSrOnly:Boolean,
    labelClass:[String,Array],


};




const computed = {

    labelFor() {
        if(this.id) {
            return this.id;
        }
        return undefined;
    },

    formGroupId() {
        if(this.id) {
            return this.id + FORM_GROUP_ID_SUFFIX;
        }
        return undefined;
    },

    tablerFormGroupProps() {
        return {
            id:this.formGroupId,
            //state:null,
            horizontal:this.horizontal,
            labelCols:this.labelCols,
            breakpoint:this.breakpoint,
            labelTextAlign:this.labelTextAlign,
            label:this.label,
            labelFor:this.labelFor,
            labelSize:this.labelSize,
            labelSrOnly:this.labelSrOnly,
            labelClass:this.labelClass,
            description:this.description,
            //invalidFeedback:null,
            //feedback:null,
            //validFeedback:null,
            //validated:null,
            required:this.required,
            requiredText:this.requiredText,


            validation:this.validation,
        };
    }


};



export default {
    computed,
    props
};