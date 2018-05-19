<template>

    <b-card no-body>

        <table class="table card-table table-hover table-sm">
            <tr>
                <th><i class="fa fa-user text-muted"></i></th>
                <td><display-person-name v-bind="person.name" /></td>
            </tr>

            <tr>
                <th><i class="fa fa-book text-muted"></i></th>
                <td></td>
            </tr>

            <tr>
                <th><i class="fa fa-birthday-cake text-muted"></i></th>
                <td><display-person-birth-date :birth_date="person.birth_date" /></td>
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

    </b-card>

</template>

<script>
    import DisplayPersonName from "../../DisplayPersonName";

    export default {
        components: {DisplayPersonName},
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