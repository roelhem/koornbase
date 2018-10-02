<template>
    <span>
        <base-field title="Telefoonnummer" name="number" class="tracking-wide">{{ phoneNumber.number }}</base-field>
        <span v-if="withLocation" class="small text-muted">
            (<base-field title="Locatie telefoonnummer" name="nlLocation">{{ phoneNumber.nlLocation }}</base-field>)
        </span>
        <phone-call-button v-if="withButton" :phone-number="phoneNumber" />
    </span>
</template>

<script>
    import gql from "graphql-tag";
    import BaseField from "./BaseField";
    import PhoneCallButton from "../features/links/PhoneCallButton";

    export default {
        components: {
            PhoneCallButton,
            BaseField},


        fragment: gql`
            fragment PhoneNumberSpan on PhoneNumber {
                number
                nlLocation:location(locale:"nl_NL")
                ...PhoneCallButton
            }
            ${PhoneCallButton.fragment}
        `,

        props: {
            phoneNumber:{
                type:Object,
                required:true,
                default() {
                    return {
                        number:null,
                        nlLocation:null
                    };
                }
            },

            withLocation:{
                type:Boolean,
                default:false,
            },

            withButton:{
                type:Boolean,
                default:false
            }
        },


        name: "phone-number-span"
    }
</script>

<style scoped>

</style>