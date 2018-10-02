<template>
    <table class="table">
        <tbody>
            <template v-for="(row, rowIndex) in rows">
                <slot name="row" :item="row" :row-index="rowIndex">
                    <person-contact-entry-table-row
                            :key="row.id"
                            :entry="row"
                    />
                </slot>
            </template>
        </tbody>
    </table>
</template>

<script>
    import PersonContactEntryTableRow from "./PersonContactEntryTableRow";

    export default {
        components: {PersonContactEntryTableRow},
        name: "person-contact-entry-table",


        props: {
            entries: {
                type:Array,
                required:true,
                default:function() {
                    return [];
                }
            }
        },

        computed: {
            rows() {
                if(this.entries) {
                    return this.entries;
                }
                return [];
            }
        }
    }
</script>

<style scoped>

</style>