<template>

    <span>
        <data-display title="Initialen">{{ name_initials }}</data-display>
        <span class="text-muted font-italic">
            (
            <data-display title="Voornaam" class="text-muted-dark">{{ name_first }}</data-display>
            <data-display title="Extra Namen">{{ name_middle }}</data-display>
            )
        </span>
        <data-display title="Tussenvoegsel">{{ name_prefix }}</data-display>
        <data-display title="Achternaam">{{ name_last }}</data-display>
    </span>

</template>

<script>
    import DataDisplay from "./displays/data-display";

    export default {
        components: {DataDisplay},
        name: "display-person-name",
        props: {
            name_initials:String,
            name_first:String,
            name_middle:String,
            name_prefix:String,
            name_last:String,
            name_nickname:String
        }
    }
</script>

<style scoped>

</style>