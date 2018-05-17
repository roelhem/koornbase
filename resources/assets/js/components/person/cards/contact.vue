<template>

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Contactgegevens</h3>
        </div>

        <div class="card-body">

            <div class="row">

                <contact-info-section title="E-mailadressen"
                                      :items="person.emailAddresses"
                                      :person="person"
                                      :display-component="require('./displays/email-address')"
                                      class="col-lg-12 my-2">
                </contact-info-section>

                <contact-info-section title="Telefoonnummers"
                                      :items="person.phoneNumbers"
                                      :display-component="require('./displays/phone-number')"
                                      class="col-lg-12 my-2">
                </contact-info-section>

            </div>

        </div>

    </div>

</template>

<script>
    export default {
        props: {
            person:Object
        },

        components:{
            'contact-info-section': require('./sections/contact-info-section'),
        }
    }
</script>

<style scoped>

</style>