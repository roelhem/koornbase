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
                    <data-display title="Status Lidmaatschap">
                        <membership-status :value="person.membership_status"></membership-status>
                    </data-display>
                    <small class="text-muted">
                        [ Sinds
                            <data-display class="text-muted-dark" title="Datum waarop de lidstatus veranderde">{{ person.membership_status_since | moment('dd D MMMM YYYY') }}</data-display>
                        ]
                    </small>
                </td>
            </tr>

            <tr>
                <th><i class="fa fa-birthday-cake text-muted"></i></th>
                <td>
                    <data-display title="Geboortedatum">{{ person.birth_date | moment('D MMMM YYYY') }}</data-display>
                    <small :class="{'text-muted': !underAged, 'text-warning': underAged}">
                        (
                        <data-display title="Leeftijd">{{ age }}</data-display>
                        )
                    </small>
                </td>
            </tr>

            <tr v-for="emailAddress in person.emailAddresses" v-if="emailAddress.is_primary">
                <th><i class="fa fa-at"></i></th>
                <td>
                    <data-display title="E-mailadres">{{ emailAddress.email_address }}</data-display>
                </td>
            </tr>

            <tr v-for="phoneNumber in person.phoneNumbers" v-if="phoneNumber.is_primary">
                <th><i class="fa fa-phone"></i></th>
                <td>
                    <data-display title="Telefoonnummer">{{ phoneNumber.formats.display }}</data-display>
                </td>
            </tr>
        </table>

    </div>

</template>

<script>
    import moment from 'moment';

    export default {
        props:{
            person:Object
        },
        computed: {
            age:function() {
                return moment().diff(this.person.birth_date, 'years');
            },
            underAged:function() {
                return this.age < 18;
            }
        }
    }
</script>

<style scoped>

</style>