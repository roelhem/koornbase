<template>

    <b-card no-body>

        <table class="table card-table table-hover table-sm">
            <tr>
                <th><i class="fa fa-user text-muted"></i></th>
                <td><display-person-name v-bind="person.name" /></td>
            </tr>

            <tr>
                <th><i class="fa fa-birthday-cake text-muted"></i></th>
                <td><display-person-birth-date :birth_date="person.birth_date" /></td>
            </tr>

            <tr>
                <th><i class="fa fa-book text-muted"></i></th>
                <td>
                    <display-person-membership-status :value="person.membership_status"
                                                      :since="person.membership_status_since">
                    </display-person-membership-status>
                </td>
            </tr>

            <tr v-if="person.cardOwnership">
                <th><i class="fa fa-credit-card text-muted"></i></th>
                <td>
                    <data-display title="Ledenpas">{{ person.cardOwnership.card.id }}</data-display>
                    <span class="small text-muted font-italic" v-if="person.cardOwnership.start">
                        ( Sinds
                        <data-display class="text-muted-dark" title="In bezit van ledenpas sinds">
                            {{ person.cardOwnership.start | moment('dd D MMM YYYY') }}
                        </data-display>
                        )
                    </span>
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