<template>

    <label :class="labelClasses">

        <input :class="inputClasses"
               :type="type"
               :name="name"
               :disabled="disabled"
               :value="value"
               v-on="inputEvents"
               v-model="modelledChecked"
               ref="input">

        <span v-if="asSwitch" class="custom-switch-indicator"
              :class="(this.isChecked && this.color) ? 'bg-'+this.color : null"></span>

        <span ref="description" :class="descriptionClasses">
            <slot></slot>
        </span>

    </label>

</template>

<script>
    export default {
        name: "custom-control-input",
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
                default:'radio',
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
            asSwitch:{
                type:Boolean,
                default:false
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

            isChecked: function() {
                if(typeof this.modelledChecked === 'boolean') {
                    return this.modelledChecked;
                } else {
                    return this.modelledChecked.indexOf(this.value) >= 0;
                }
            },

            labelClasses: function() {
                let res = [];

                if(this.asSwitch) {
                    res.push('custom-switch');
                    if(this.disabled) {
                        res.push('custom-switch-disabled');
                    }
                } else {
                    res.push('custom-control');
                    res.push('custom-'+this.type);
                    if(this.inline) {
                        res.push('custom-control-inline');
                    }
                }

                return res.join(' ');
            },

            inputClasses: function() {
                let res = [];

                if(this.asSwitch) {
                    res.push('custom-switch-input');
                } else {
                    res.push('custom-control-input');
                }

                return res.join(' ');
            },

            descriptionClasses: function() {
                let res = [];

                if(this.asSwitch) {
                    res.push('custom-switch-description');
                } else {
                    res.push('custom-control-label');
                }

                return res.join(' ');
            }
        },
    }
</script>

<style scoped>

    .custom-switch-disabled {
        opacity: 0.5;
    }

</style>