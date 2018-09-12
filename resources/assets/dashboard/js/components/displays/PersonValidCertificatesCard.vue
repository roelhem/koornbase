<template>

    <tabler-card no-body no-header>

        <template v-if="certificateCount > 0">
            <certificate-table-small in-card
                                     :certificates="certificates"
            />
        </template>

        <template v-else>
            <div class="p-2 text-center">
                <span class="text-muted font-italic">(Geen geldige certificaten)</span>
            </div>
        </template>

    </tabler-card>

</template>

<script>
    import gql from "graphql-tag";

    import TablerCard from "../layouts/cards/TablerCard";
    import CertificateTableSmall from "./CertificateTableSmall";

    export default {
        components: {
            CertificateTableSmall,
            TablerCard
        },

        fragment: gql`
            fragment PersonValidCertificatesCard on Person {
                validCertificates: certificates(filter:{isValid:true}) {
                   total
                   ...CertificateTableSmall
                }
            }
            ${CertificateTableSmall.fragment}
        `,

        props: {
            person:{
                type:Object,
                default:function() {
                    return {
                        validCertificates:{
                            total:0,
                            data:[]
                        }
                    }
                }
            }
        },

        computed: {

            certificateCount() {
                if(this.person.validCertificates) {
                    return this.person.validCertificates.total;
                }
                return 0;
            },


            certificates() {
                return this.person.validCertificates;
            }
        },

        name: "person-valid-certificates-card"
    }
</script>

<style scoped>

</style>