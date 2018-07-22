<template>

    <b-row class="justify-content-center">

        <b-col lg="10">
            <show-email-addresses-of-person-card :collapsed.sync="emailAddressesCollapsed" :person-id="personId" />

            <show-phone-numbers-of-person-card :collapsed.sync="phoneNumbersCollapsed" :person-id="personId" />

            <show-addresses-of-person-card :collapsed.sync="addressesCollapsed" :person-id="personId" />
        </b-col>

    </b-row>

</template>

<script>
    import ShowEmailAddressesOfPersonCard from "../ShowEmailAddressesOfPersonCard";
    import TablerDimmer from "../TablerDimmer";
    import ShowPhoneNumbersOfPersonCard from "../ShowPhoneNumbersOfPersonCard";
    import ShowAddressesOfPersonCard from "../ShowAddressesOfPersonCard";

    export default {
        components: {
            ShowAddressesOfPersonCard,
            ShowPhoneNumbersOfPersonCard,
            TablerDimmer,
            ShowEmailAddressesOfPersonCard},
        name: "page-person-contact",

        data:function() {
            return {
                emailAddressesCollapsed:false,
                phoneNumbersCollapsed:false,
                addressesCollapsed:false
            };
        },

        props: {
            personId:[Number,String]
        }
    }
</script>

<style scoped>

</style>