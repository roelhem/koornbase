<template>

    <tabler-input-icon prepend="calendar" from="fa">
        <b-form-input v-model="value"
                      :placeholder="thePlaceholder"
        ></b-form-input>
    </tabler-input-icon>

</template>

<script>
    import TablerInputIcon from "./TablerInputIcon";

    export default {
        components: {TablerInputIcon},
        name: "date-picker-input",


        props: {

            inputValue:String,

            updateValue:{
                default:function() {
                    return (newValue, options) => {
                        console.log('default updateValue function called for a `date-picker-input`.', newValue, options);
                    }
                }
            },



            // OTHER PROPS
            placeholder:String,

            mode:{
                type:String,
                default:'single',
            },


        },

        computed: {

            value: {
                get() {
                    return this.inputValue;
                    },
                set(newValue) {
                    this.updateValue(newValue, { hidePopover:false });
                }
            },

            defaultPlaceholder() {
                const format = 'DD-MM-YYYY';
                switch(this.mode) {
                    case 'single': return format;
                    case 'range': return format+' - '+format;
                    case 'multiple': return format+', '+format+', '+format+'...';
                }
            },

            thePlaceholder() {
                if(typeof this.placeholder === 'string') {
                    return this.placeholder;
                }
                return this.defaultPlaceholder;
            }

        }

    }
</script>

<style scoped>

</style>