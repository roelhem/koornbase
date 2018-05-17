<template>

    <form-table-input name="emailAddresses"
                      :columns="columns"
                      :rows="value"
                      @change="changeHandler" />

</template>

<script>
    import FormTableInput from "../../forms/form-table-input";
    import FormTableSelectCell from "../../forms/form-table-select-cell";
    import CustomControlInput from "../../forms/custom-control-input";
    import EmailInput from "../../forms/email-input";
    import TypeaheadInput from "../../forms/typeahead-input";

    export default {

        props: {
            value: {
                type:Array,
                default:function() {
                    return [];
                }
            }
        },

        data:function() {
            return {
                columns:[
                    {
                        name:"is_primary",
                        label:"Primair",
                        headerClass:['w-1'],
                        cellClass:['text-center'],
                        component: FormTableSelectCell,
                        props: {
                            globalName:'primary_email_address',
                        },
                        defaultValue:() => {
                            return this.value.length <= 0;
                        },
                    },
                    {
                        name:"label",
                        label:"Label",
                        component: TypeaheadInput,
                        defaultValue:null,
                    },
                    {
                        name:"email_address",
                        label:"E-mailadres",
                        component: EmailInput,
                        defaultValue:null,
                    },
                    {
                        name:"for_emergency",
                        label:"Voor nood",
                        cellClass:['text-center'],
                        component:CustomControlInput,
                        props: {
                            inline: true,
                            asSwitch: true,
                            type: 'checkbox',
                            color: 'red'
                        },
                        defaultValue:false
                    },
                ],
            };
        },

        methods: {
            changeHandler: function( val ) {
                this.$emit('input', val);
            }
        },

        components: {
            FormTableInput,
        }
    }
</script>

<style scoped>

</style>