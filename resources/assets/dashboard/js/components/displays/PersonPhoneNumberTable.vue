<template>
    <person-contact-entry-table :entries="phoneNumbers">
        <person-phone-number-table-row
                slot="row"
                slot-scope="{item}"
                :key="item.id"
                :phone-number="item"
        />
    </person-contact-entry-table>
</template>

<script>
    import PersonContactEntryTable from "./PersonContactEntryTable";
    import PersonPhoneNumberTableRow from "./PersonPhoneNumberTableRow";

    export default {
        components: {
            PersonPhoneNumberTableRow,
            PersonContactEntryTable
        },

        name: "person-phone-number-table",

        props: {
            phoneNumbers:{
                type:Array,
                required:true,
                default() { return []; }
            }
        }
    }
</script>

<style scoped>

</style>