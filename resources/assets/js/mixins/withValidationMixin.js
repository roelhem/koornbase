import {rulesFeedback} from "../filters/validation";

export const VALID_STATE = 'valid';
export const INVALID_STATE = 'invalid';

const props = {

    // Validation
    validation:Object,

};


/** Function that returns a new object that only contains the keys from the keys array. */
function onlyKeys(object, keys) {
    let res = {};
    for(let i=0; i<keys.length; i++) {
        const key = keys[i];
        if(key in object) {
            res[key] = object[key];
        }
    }
    return res;
}



const computed = {

    /** Whether or not the validation should be applied.  */
    ignoreValidation() {
        if(!this.validation) { return true; }
        if(!this.validation.$dirty) { return true; }
        return false;
    },

    /** The rules in the validation object. */
    validationRules() {
        if(this.validation && this.validation.$params) {
            return this.validation.$params;
        }
        return {};
    },

    /** An array of the keys that represent a validation rule. */
    validationRuleKeys() {
        let res = [];
        for(let key in this.validationRules) {
            if(this.validationRules.hasOwnProperty(key)) {
                res.push(key);
            }
        }
        return res;
    },

    /** An array of the rule-keys that were violated. */
    violatedRuleKeys() {
        if(this.ignoreValidation) { return []; }
        return this.validationRuleKeys.filter(key => this.validation.hasOwnProperty(key) && !this.validation[key]);
    },

    /** Returns only the rules that were violated.  */
    violatedRules() {
        return onlyKeys(this.validationRules, this.violatedRuleKeys);
    },

    /** An array of the rule-keys that were furfilled. */
    obeyedRuleKeys() {
        if(this.ignoreValidation) { return []; }
        return this.validationRuleKeys.filter(key => this.validation[key]);
    },

    /** Returns only the rules that were obeyed.  */
    obeyedRules() {
        return onlyKeys(this.validationRules, this.obeyedRuleKeys);
    },



    validationRuleCount() { return this.validationRuleKeys.length; },
    hasValidationRules() { return this.validationRuleCount > 0; },

    violatedRuleCount() { return this.violatedRuleKeys.length; },
    hasViolatedRules() { return this.violatedRuleCount > 0; },

    obeyedRuleCount() { return this.obeyedRuleKeys.length; },
    hasObeyedRules() { return this.obeyedRuleCount > 0; },



    /** Returns if the field is valid. */
    isValid() {
        if(this.ignoreValidation) {
            return false;
        }

        if(typeof this.validation.$invalid === 'boolean') {
            return !this.validation.$invalid;
        }

        return this.hasValidationRules && this.hasObeyedRules && this.obeyedRuleCount === this.validationRuleCount;
    },

    /** Returns if the field is invalid. */
    isInvalid() {
        if(this.ignoreValidation) {
            return false;
        }

        if(typeof this.validation.$invalid === 'boolean') {
            return this.validation.$invalid;
        }

        return this.hasViolatedRules;
    },

    /** Returns the current state of the field. */
    validationState() {
        if(this.isInvalid) { return INVALID_STATE; }
        if(this.isValid) { return VALID_STATE; }
        return null;
    },


    /** Returns the invalid feedback string. */
    invalidFeedbackString() {
        return rulesFeedback(this.violatedRules);
    }

};



export default {
    props, computed
};