<template>

    <div class="container my-4">

        <div class="row">

            <div class="col-lg-4">
                <person-card-info-small :person="person"></person-card-info-small>
            </div>

            <div class="col-lg-8">

            </div>

        </div>

    </div>

</template>

<script>
    export default {

        props:{
            person:Object
        },

        components: {
            'person-card-info-small': require('../cards/info-small')
        }

    }
</script>

<style scoped>

</style>