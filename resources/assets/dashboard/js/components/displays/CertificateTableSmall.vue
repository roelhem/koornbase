<template>
    <table class="table table-sm" :class="{'card-table':inCard}">

        <tbody>
            <certificate-table-small-row
                    v-for="row in rows"
                    :key="row.id"
                    :certificate="row"
            />
        </tbody>

    </table>
</template>

<script>
    import gql from "graphql-tag";
    import CertificateTableSmallRow from "./CertificateTableSmallRow";

    export default {
        components: {CertificateTableSmallRow},
        name: "certificate-table-small",

        fragment:gql`
            fragment CertificateTableSmall on Certificate_pagination {
                data {
                    id
                    ...CertificateTableSmallRow
                }
            }
            ${CertificateTableSmallRow.fragment}
        `,

        props: {
            certificates:{
                type:Object,
                default:function() {
                    return {
                        data:[],
                    }
                }
            },

            inCard:{
                type:Boolean,
                default:false
            }
        },

        computed: {
            rows() {
                return this.certificates.data;
            }
        }
    }
</script>

<style scoped>

</style>