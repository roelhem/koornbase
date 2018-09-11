<template>

    <label :class="labelClasses">

        <input class="custom-switch-input"
               :type="type"
               :name="name"
               :disabled="disabled"
               :value="value"
               v-on="inputEvents"
               v-model="modelledChecked"
               ref="input">

        <span class="custom-switch-indicator"></span>

        <span ref="description" class="custom-switch-description">
            <slot></slot>
        </span>

    </label>

</template>

<script>
    export default {
        name: "form-switch",
        model: {
            prop:'checked',
            event:'change'
        },

        props: {
            value:{
                default:'1'
            },
            name:String,
            type:{
                type:String,
                default:'checkbox',
                validator:function(value) {
                    return ['radio','checkbox'].indexOf(value) !== -1;
                }
            },
            inline:{
                type:Boolean,
                default:false
            },
            color:{
                type:String
            },
            checked:{
                default:false,
            },
            inputEvents:{
                type:Object,
                default:function() {
                    return {}
                }
            },
            disabled:{
                type:Boolean,
                default:false
            }
        },

        computed: {

            modelledChecked: {
                get: function() {
                    return this.checked;
                },
                set: function(newValue) {
                    this.$emit('change', newValue);
                }
            },

            labelClasses: function() {
                let res = [];
                res.push('custom-switch');
                if(this.color) {
                    res.push('custom-switch-'+this.color);
                }
                if(this.disabled) {
                    res.push('custom-switch-disabled');
                }
                return res;
            },

        },
    }
</script>

<style scoped>

    .custom-switch-disabled {
        opacity: 0.5;
    }

</style>