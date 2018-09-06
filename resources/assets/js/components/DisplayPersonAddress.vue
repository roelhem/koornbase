<template>

    <span>
        <data-display title="Adres">{{ address_line_1 }}</data-display><span class="text-muted">,</span>
        <data-display title="Plaats" class="font-italic">{{ locality }}</data-display>
        <span class="text-muted small" v-if="country_code !== 'NL'">
            (<data-display title="Land">{{ country }}</data-display>)
        </span>
    </span>

</template>

<script>
    import DataDisplay from "./displays/data-display";

    export default {
        components: {DataDisplay},
        name: "display-person-address",

        props: {
            country_code:String,
            country:String,
            locality:String,
            address_line_1:String
        }
    }
</script>

<style scoped>

</style>