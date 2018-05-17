<template>

    <div class="container my-4">

        <div class="row">

            <div class="col-lg-6">
                <person-contact-card :person="person"></person-contact-card>
            </div>

            <div class="col-lg-6">

            </div>

        </div>

    </div>

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