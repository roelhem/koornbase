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
    import gql from 'graphql-tag';
    import PersonContactEntryTable from "./PersonContactEntryTable";
    import PersonPhoneNumberTableRow from "./PersonPhoneNumberTableRow";

    export default {
        components: {
            PersonPhoneNumberTableRow,
            PersonContactEntryTable
        },

        name: "person-phone-number-table",

        fragment:gql`
            fragment PersonPhoneNumberTable on PersonPhoneNumber_pagination {
                data {
                    ...PersonPhoneNumberTableRow
                }
            }
            ${PersonPhoneNumberTableRow.fragment}
        `,

        props: {
            phoneNumbers:Object
        }
    }
</script>

<style scoped>

</style>