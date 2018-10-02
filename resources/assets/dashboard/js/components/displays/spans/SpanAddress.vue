<template>

    <span>
        <base-field title="Adres" name="addressLine1">{{ address.addressLine1 }}</base-field><span class="text-muted">,</span>
        <base-field title="Plaats" name="locality" class="font-italic">{{ address.locality }}</base-field>
        <span class="text-muted small" v-if="showCountry">
            (<base-field title="Land" name="country">{{ address.country.name }}</base-field>)
        </span>
    </span>

</template>

<script>
    import gql from "graphql-tag";
    import BaseField from "../BaseField";

    export default {
        components: {
            BaseField,
        },
        name: "span-address",

        fragment:gql`
            fragment SpanAddress on PostalAddress {
                addressLine1
                locality
                country {
                    name
                    code
                }
            }
        `,

        props: {
            address:{
                type:Object,
                default:function() {
                    return {
                        country:{},
                        locality:null,
                        addressLine1:null
                    }
                }
            },

            defaultCountryCode:{
                type:String,
                default:"NL"
            },
        },

        computed: {
            showCountry() {
                return this.address.country.code !== this.defaultCountryCode;
            }
        }
    }
</script>

<style scoped>

</style>