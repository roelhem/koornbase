<template>
    <tr>

        <th class="font-weight-bold">
            {{ entry.index + 1 }}
        </th>


        <th>
            <base-field title="Label" name="label">{{ entry.label }}</base-field>
        </th>

        <slot />

    </tr>
</template>

<script>
    import gql from "graphql-tag";
    import BaseField from "./BaseField";

    export default {
        components: {BaseField},
        name: "person-contact-entry-table-row",

        fragment: gql`
            fragment PersonContactEntryTableRow on PersonContactEntry {
                index
                label
            }
        `,

        props: {
            entry: {
                type:Object,
                required:true,
                default:function() {
                    return {
                        index: -2,
                        label:null,
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>