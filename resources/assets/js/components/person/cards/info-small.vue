<template>

    <div class="card">

        <table class="table card-table table-sm">
            <tr>
                <th><i class="fa fa-user text-muted"></i></th>
                <td>
                    <data-display title="Initialen">{{ person.name.initials }}</data-display>
                    <span class="text-muted font-italic">
                        (
                        <data-display title="Voornaam" class="text-muted-dark">{{ person.name.first }}</data-display>
                        <data-display title="Extra Namen">{{ person.name.middle }}</data-display>
                        )
                    </span>
                    <data-display title="Tussenvoegsel">{{ person.name.prefix }}</data-display>
                    <data-display title="Achternaam">{{ person.name.last }}</data-display>
                </td>
            </tr>

            <tr>
                <th><i class="fa fa-book text-muted"></i></th>
                <td>
                    <data-display title="Status Lidmaatschap"><membership-status :value="person.membership_status"></membership-status></data-display>
                </td>
            </tr>
        </table>

    </div>

</template>

<script>
    export default {
        props:{
            person:Object
        }
    }
</script>

<style scoped>

</style>