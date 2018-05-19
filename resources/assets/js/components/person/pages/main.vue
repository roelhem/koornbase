<template>

    <b-container class="my-4">

        <b-row>

            <b-col lg="4">
                <person-card-info-small :person="person"></person-card-info-small>
            </b-col>

            <b-col>
                <b-card no-body>
                    <b-table class="card-table" :items="person.groupMemberships">

                    </b-table>
                </b-card>
            </b-col>

        </b-row>

    </b-container>

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