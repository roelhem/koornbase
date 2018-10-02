<template>

    <b-row>

        <b-col lg="4">

            <person-details-card-small :person="person" />

            <person-valid-certificates-card :person="person" />


        </b-col>

        <b-col lg="8">

            <person-detail-card :person="person" />

        </b-col>
    </b-row>

</template>

<script>
    import gql from "graphql-tag";

    import PersonDetailsCardSmall from "../../components/displays/PersonDetailsCardSmall";
    import CertificateTableSmall from "../../components/displays/CertificateTableSmall";
    import PersonValidCertificatesCard from "../../components/displays/PersonValidCertificatesCard";
    import PersonDetailCard from "../../components/displays/PersonDetailCard";

    export default {
        components: {
            PersonDetailCard,
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
                            ...PersonDetailCard
                            ...PersonDetailsCardSmall
                            ...PersonValidCertificatesCard

                        }
                    }
                    ${PersonDetailCard.fragment}
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
                    emailAddresses:[],
                    addresses:[],
                    phoneNumbers:[],
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