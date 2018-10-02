<template>

    <person-contact-entry-table :entries="emailAddresses">
        <person-email-address-table-row
                slot="row"
                slot-scope="{item}"
                :key="item.id"
                :email-address="item"
        />
    </person-contact-entry-table>

</template>

<script>

    import PersonEmailAddressTableRow from "./PersonEmailAddressTableRow";
    import PersonContactEntryTable from "./PersonContactEntryTable";

    export default {

        name:'person-email-address-table',

        props: {
            emailAddresses: {
                type:Array,
                required:true,
                default() {
                    return [];
                }
            }
        },

        components: {
            PersonContactEntryTable,
            PersonEmailAddressTableRow
        },

    }
</script>

<style scoped>

</style>