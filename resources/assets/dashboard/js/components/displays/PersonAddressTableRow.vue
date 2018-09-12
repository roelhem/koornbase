<template>
    <person-contact-entry-table-row :entry="address">

        <td v-html="address.html"></td>

    </person-contact-entry-table-row>
</template>

<script>
    import gql from 'graphql-tag';
    import PersonContactEntryTableRow from "./PersonContactEntryTableRow";

    export default {
        name: "person-address-table-row",

        components: {
            PersonContactEntryTableRow
        },

        fragment: gql`
            fragment PersonAddressTableRow on PersonAddress {
                id
                ...PersonContactEntryTableRow
                html:format(html:true)
            }
            ${PersonContactEntryTableRow.fragment}
        `,

        props: {
            address:{
                type:Object,
                required:true,
                default() {
                    return {
                        index:null,
                        label:null,
                        html:null,
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>