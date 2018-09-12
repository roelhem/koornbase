<template>

    <span>
        <base-field title="Adres" name="address_line_1">{{ address.address_line_1 }}</base-field><span class="text-muted">,</span>
        <base-field title="Plaats" name="locality" class="font-italic">{{ address.locality }}</base-field>
        <span class="text-muted small" v-if="showCountry">
            (<base-field title="Land" name="country">{{ address.country }}</base-field>)
        </span>
    </span>

</template>

<script>
    import BaseField from "../BaseField";

    export default {
        components: {
            BaseField,
        },
        name: "span-address",

        props: {
            address:{
                type:Object,
                default:function() {
                    return {
                        country_code:null,
                        country:null,
                        locality:null,
                        address_line_1:null
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
                return this.address.country_code !== this.defaultCountryCode;
            }
        }
    }
</script>

<style scoped>

</style>