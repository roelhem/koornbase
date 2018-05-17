<template>

    <crud-form :title="formTitle" method="post" :is-loading="isLoading">

        <person-form-name v-model="person.name"></person-form-name>

        <fieldset>

            <legend>Overige Gegevens</legend>

            <form-group label="Geboortedatum">
                <v-date-picker v-model="birth_date_formated" :input-props="{class:'form-control',name:'birth_date'}"></v-date-picker>
            </form-group>


        </fieldset>

        <fieldset>
            <legend>E-mailadressen</legend>

            <emailadres-table v-model="person.emailAddresses"></emailadres-table>
        </fieldset>

        <fieldset>
            <legend>Telefoonnummers</legend>

            <telephone-table v-model="person.phoneNumbers"></telephone-table>
        </fieldset>

        <fieldset>

            <legend>Opmerkingen</legend>

            <f-textarea name="remarks"></f-textarea>

        </fieldset>

    </crud-form>

</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import FormGroup from "../../forms/form-group";
    import FormTableInput from "../../forms/form-table-input";
    import EmailadresTable from "./emailadres-table";
    import TelephoneTable from "./telephone-table";

    export default {
        props:{
            action:String,
        },

        data: function() {
            return {
                isLoading: false,
                person: {}
            }
        },

        computed: {
            formTitle: function () {
                if(this.action === 'create') {
                    return 'Persoon Toevoegen';
                } else {
                    return 'Persoon Bewerken';
                }
            },
            birth_date_formated:{
                get:function() {
                    const birth_date = this.person.birth_date;
                    if(birth_date) {
                        return moment(this.person.birth_date).toDate();
                    } else {
                        return null;
                    }
                },
                set:function(newValue) {
                    this.person.birth_date = moment(newValue).toISOString();
                }
            }
        },

        methods: {

            reload() {
                this.isLoading = true;
                axios.get(window.location.href).then(response => {

                    this.person = response.data.data;

                    this.isLoading = false;
                }).catch(error => {
                    console.log(error);

                    this.isLoading = false;
                });
            }

        },

        created() {
            this.reload();
        },

        components: {
            EmailadresTable, TelephoneTable,
            FormTableInput,
            FormGroup,
            'person-form-name': require('./name'),
        }
    }
</script>

<style scoped>

</style>