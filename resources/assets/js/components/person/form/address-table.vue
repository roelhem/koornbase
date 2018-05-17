<template>

    <form-table-input name="email_addresses" :columns="columns" v-model="rows" />

</template>

<script>
    import FormTableInput from "../../forms/form-table-input";
    import FormTableSelectCell from "../../forms/form-table-select-cell";
    import CustomControlInput from "../../forms/custom-control-input";
    import EmailInput from "../../forms/email-input";
    import TypeaheadInput from "../../forms/typeahead-input";

    export default {

        data:function() {
            return {
                columns:[
                    {
                        name:"is_primary",
                        headerClass:['w-1'],
                        component: FormTableSelectCell,
                        props: {
                            globalName:'primary_email_address',
                        },
                        defaultValue:false,
                    },
                    {
                        name:"label",
                        label:"Label",
                        component: TypeaheadInput,
                        defaultValue:null
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
                rows:[
                    {
                        is_primary:false,
                        for_emergency:false,
                        email_address: 'ik@roelweb.com',
                    },
                    {
                        is_primary:true,
                        for_emergency:false,
                        email_address: 'roelhemerik@gmail.com',
                    },
                    {
                        is_primary:false,
                        for_emergency:true,
                        email_address: 'hennyvanwieringen@gmail.com',
                    }
                ]
            };
        },

        methods: {
            changeHandler: function( rows ) {
                console.log(rows);
            }
        },

        components: {
            FormTableInput,
        }
    }
</script>

<style scoped>

</style>