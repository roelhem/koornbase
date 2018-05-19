<template>

    <span>
        <data-display title="Initialen">{{ initials }}</data-display>
        <span class="text-muted font-italic">
            (
            <data-display title="Voornaam" class="text-muted-dark">{{ first }}</data-display>
            <data-display title="Extra Namen">{{ middle }}</data-display>
            )
        </span>
        <data-display title="Tussenvoegsel">{{ prefix }}</data-display>
        <data-display title="Achternaam">{{ last }}</data-display>
    </span>

</template>

<script>
    export default {
        name: "display-person-name",
        props: {
            initials:String,
            first:String,
            middle:String,
            prefix:String,
            last:String,
            nickname:String
        }
    }
</script>

<style scoped>

</style>