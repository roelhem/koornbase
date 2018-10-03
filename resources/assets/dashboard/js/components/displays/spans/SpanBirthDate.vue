<template>

    <span :class="{'d-inline-block':stacked}">
        <base-field title="Geboortedatum">{{ birthDate | date('bday') }}</base-field>
        <small class="font-italic" :class="{'text-muted': !underAged, 'text-orange': underAged,'d-block':stacked}">
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
            birthDate:{
                required:true,
            },

            stacked:{
                type:Boolean,
                default:false
            }
        },

        computed: {
            age:function() {
                return moment().diff(this.birthDate, 'years');
            },
            underAged:function() {
                return this.age < 18;
            }
        }
    }
</script>

<style scoped>

</style>