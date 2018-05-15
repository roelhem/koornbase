<template>

    <div class="p-2">
        <p class="text-muted-dark text-right" v-if="meta.from && meta.to && meta.total">
            Records
            <span class="meta-value">{{meta.from}}</span>
            t/m
            <span class="meta-value">{{meta.to}}</span>
            van de in totaal
            <span class="meta-value">{{ meta.total }}</span>
            gevonden personen.
        </p>
        <p class="text-muted-dark text-right" v-else>
            Geen personen gevonden.
        </p>
    </div>

</template>

<script>
    export default {
        name: "people-search-meta",
        props: {
            meta:Object
        }
    }
</script>

<style scoped>

    .meta-value {
        font-weight: 600;
        margin-left: 4px;
        margin-right: 4px;
    }

</style>