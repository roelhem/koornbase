<template>

    <b-row>

        <b-col lg="4">

            <person-details-card-small :person="person" />

            <person-valid-certificates-card :person="person" />


        </b-col>

        <b-col lg="8">

        </b-col>
    </b-row>

</template>

<script>
    import gql from "graphql-tag";

    import PersonDetailsCardSmall from "../../components/displays/PersonDetailsCardSmall";
    import CertificateTableSmall from "../../components/displays/CertificateTableSmall";
    import PersonValidCertificatesCard from "../../components/displays/PersonValidCertificatesCard";

    export default {
        components: {
            PersonValidCertificatesCard,
            CertificateTableSmall,
            PersonDetailsCardSmall,
        },

        apollo: {
            person:{
                query:gql`
                    query viewPersonOverview($id:ID!) {
                        person(id:$id) {
                            id
                            ...PersonDetailsCardSmall
                            ...PersonValidCertificatesCard
                        }
                    }
                    ${PersonDetailsCardSmall.fragment}
                    ${PersonValidCertificatesCard.fragment}
                `,
                variables() {
                    return {
                        id:this.personId
                    };
                }
            }
        },

        data() {
            return {
                person:{
                    emailAddresses:{data:[]},
                    addresses:{data:[]},
                    phoneNumbers:{data:[]},
                }
            }
        },

        props: {
            personId:{
                type:[String, Number]
            }
        }
    }
</script>

<style scoped>

</style>