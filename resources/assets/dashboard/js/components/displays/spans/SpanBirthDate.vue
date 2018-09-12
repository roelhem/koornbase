<template>

    <span>
        <base-field title="Geboortedatum">{{ birth_date | date('bday') }}</base-field>
        <small class="font-italic" :class="{'text-muted': !underAged, 'text-danger': underAged}">
            (
            <base-field title="Leeftijd">{{ age }} jaar</base-field>
            )
        </small>
    </span>


</template>

<script>
    import moment from 'moment';
    import displayFilters from '../../../utils/filters/display';
    import BaseField from "../BaseField";

    export default {
        components: {
            BaseField
        },

        filters:displayFilters,

        name: "span-birth-date",

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