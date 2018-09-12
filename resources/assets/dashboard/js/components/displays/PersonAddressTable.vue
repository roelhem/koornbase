<template>

    <person-contact-entry-table :entries="addresses">
        <person-address-table-row
                slot="row"
                slot-scope="{item}"
                :key="item.id"
                :address="item"
        />
    </person-contact-entry-table>

</template>

<script>
    import gql from 'graphql-tag';
    import PersonContactEntryTable from "./PersonContactEntryTable";
    import PersonAddressTableRow from "./PersonAddressTableRow";

    export default {

        name: "person-address-table",

        props: {
            addresses:Object,
        },

        fragment:gql`
            fragment PersonAddressTable on PersonAddress_pagination {
                data {
                    ...PersonAddressTableRow
                }
            }
            ${PersonAddressTableRow.fragment}
        `,

        components: {
            PersonAddressTableRow,
            PersonContactEntryTable
        },
    }
</script>

<style scoped>

</style>