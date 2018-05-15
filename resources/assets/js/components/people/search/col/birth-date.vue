<template>

    <td>
        <div>{{ row.birth_date | moment('D MMMM Y') }}</div>
        <div class="small font-italic" :class="{'text-danger': underAged, 'text-muted': !underAged}">
            ( {{ age }} jaar )
        </div>
    </td>

</template>

<script>
    import moment from 'moment';

    export default {
        name: "people-search-col-birth-date",
        props: {
            row:Object
        },
        computed: {
            age:function() {
                return moment().diff(this.row.birth_date, 'years');
            },
            underAged:function() {
                return this.age < 18;
            }
        }
    }
</script>

<style scoped>

</style>