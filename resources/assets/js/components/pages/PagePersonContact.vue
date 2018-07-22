<template>

    <b-row>

        <b-col lg="6">
            <show-email-addresses-of-person-card :person="person" />
        </b-col>

    </b-row>

</template>

<script>
    import ShowEmailAddressesOfPersonCard from "../ShowEmailAddressesOfPersonCard";
    import TablerDimmer from "../TablerDimmer";

    export default {
        components: {
            TablerDimmer,
            ShowEmailAddressesOfPersonCard},
        name: "page-person-contact",

        props: {
            person:Object
        }
    }
</script>

<style scoped>

</style>