<template>

    <span>
        <data-display title="Geboortedatum">{{ birth_date | moment('D MMMM YYYY') }}</data-display>
        <small class="font-italic" :class="{'text-muted': !underAged, 'text-warning': underAged}">
            (
            <data-display title="Leeftijd">{{ age }} jaar</data-display>
            )
        </small>
    </span>


</template>

<script>
    import moment from 'moment';
    import DataDisplay from "./displays/data-display";

    export default {
        components: {DataDisplay},
        name: "display-person-birth-date",
        props:{
            birth_date:null
        },
        computed: {
            age:function() {
                return moment().diff(this.birth_date, 'years');
            },
            underAged:function() {
                return this.age < 18;
            }
        }
    }
</script>

<style scoped>

</style>