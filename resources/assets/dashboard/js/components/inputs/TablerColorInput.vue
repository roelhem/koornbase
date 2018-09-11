<template>
    <label class="colorinput">
        <input :id="id"
               :name="name"
               :type="type"
               class="colorinput-input"
               :class="inputClass"
               v-model="modelChecked"
               :value="inputValue"
        />
        <span class="colorinput-color" :class="bgColorClass" :style="bgColorStyle"></span>
    </label>
</template>

<script>
    export default {
        name: "tabler-color-input",


        model: {
            prop:'checked',
            event:'change'
        },

        props: {
            id:String,
            name:String,
            inputClass:[String,Array],
            checked:{
                default:false,
            },


            color:String,
            colorStyle:Object,

            value:String,

            radio:{
                type:Boolean,
                default:false,
            }
        },

        computed: {

            modelChecked: {
                get() { return this.checked; },
                set(newValue) { return this.$emit('change', newValue); },
            },



            type() { return this.radio ? 'radio' : 'checkbox'; },

            inputValue() {
                if(typeof this.value === 'string') {
                    return this.value;
                }
                return this.color;
            },

            bgColorClass() {
                if(this.colorClass) {
                    return this.colorClass;
                }
                return 'bg-'+this.color;
            },

            bgColorStyle() {
                if(this.colorStyle) {
                    return this.colorStyle;
                }
                return {};
            },
        }
    }
</script>

<style scoped>

</style>