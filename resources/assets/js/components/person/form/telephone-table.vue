<template>

    <form-table-input name="phoneNumbers"
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
                        cellClass:['text-center'],
                        headerClass:['w-1'],
                        component: FormTableSelectCell,
                        props: {
                            globalName:'primary_telephone_number',
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
                        name:"phone_number",
                        label:"Telefoonnummer",
                        component: EmailInput,
                        defaultValue:null,
                    },
                    {
                        name:"is_mobile",
                        label:"Mobiel",
                        cellClass:['text-center'],
                        component: CustomControlInput,
                        defaultValue:false,
                        props: {
                            type:'checkbox',
                            inline:true,
                        }
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