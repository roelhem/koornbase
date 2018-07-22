<template>

    <b-row>

        <b-col lg="4">

            <show-person-details-card-small :person-id="personId" />

            <show-valid-certificates-of-person-card-small :person-id="personId" />

        </b-col>


        <b-col>

        </b-col>


    </b-row>

</template>

<script>
    import ShowPersonDetailsCardSmall from "../ShowPersonDetailsCardSmall";
    import ShowValidCertificatesOfPersonCardSmall from "../ShowValidCertificatesOfPersonCardSmall";

    export default {
        components: {
            ShowValidCertificatesOfPersonCardSmall,
            ShowPersonDetailsCardSmall,
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