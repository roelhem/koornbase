<template>

    <form-layout v-bind="formLayoutProps" v-on="formLayoutListeners">

        <input-group-person-name :id="getFieldId('name')" />

        <form-simple-input label="Bijnaam" />

        <form-date-picker-input label="Geboortedatum" />

        <form-textarea-input label="Opmerkingen" />

    </form-layout>

</template>

<script>
    import FormLayout from "../../layouts/forms/FormLayout";
    import controlForm from "../../../mixins/controlForm";
    import InputGroupPersonName from "../../inputs/groups/InputGroupPersonName";
    import FormDatePickerInput from "../../inputs/FormDatePickerInput";
    import FormTextareaInput from "../../inputs/FormTextareaInput";
    import FormSimpleInput from "../../inputs/FormSimpleInput";

    export default {
        components: {
            FormSimpleInput,
            FormTextareaInput,
            FormDatePickerInput,
            InputGroupPersonName,
            FormLayout
        },

        name: "create-person-form",

        form:{
            actionType:"create",
            title:"Nieuw Persoon Toevoegen",
            values() {
                return {
                    firstName:null,
                    middleName:null,
                    prefixName:null,
                    lastName:null,
                    initials:null,
                    nickname:null,
                    birthDate:null,
                    remarks:null,
                };
            }
        },

        mixins:[controlForm]
    }
</script>

<style scoped>

</style>