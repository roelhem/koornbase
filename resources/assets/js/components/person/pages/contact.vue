<template>

    <b-container class="my-4">

        <b-row>

            <b-col lg="6">
                <person-contact-card :person="person"></person-contact-card>
            </b-col>

            <b-col>

            </b-col>

        </b-row>

    </b-container>

</template>

<script>
    export default {

        props:{
            person:Object
        },

        components:{
            'person-contact-card': require('../cards/contact')
        }
    }
</script>

<style scoped>

</style>