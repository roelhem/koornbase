<template>

    <tabler-form-group v-bind="tablerFormGroupProps">
        <v-date-picker v-bind="$attrs" v-on="$listeners">
            <date-picker-input slot-scope="sc" v-bind="sc" />
        </v-date-picker>
    </tabler-form-group>

</template>

<script>
    import formGroupMixin from "../../mixins/formGroupMixin";
    import TablerFormGroup from "../layouts/forms/TablerFormGroup";
    import DatePickerInput from "./DatePickerInput";

    export default {
        components: {
            DatePickerInput,
            TablerFormGroup
        },
        name: "form-date-picker-input",
        mixins:[formGroupMixin]
    }
</script>

<style scoped>

</style>