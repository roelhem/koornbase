<template>

    <pre>{{ person }}</pre>

</template>

<script>
    import gql from 'graphql-tag';


    export default {
        name: "page-person-debug",

        apollo: {
            person: {
                query: gql`query getPersonDebugInfo($id:ID!) {
                    person(id:$id) {
                        id name membership_status membership_status_since
                    }
                }`,
                variables() {
                    return { id: this.personId}
                }
            },
        },

        data: function() {
            return {
                person: {},
            }
        },


        props: {
            personId: {
                type:[String,Number]
            }
        }
    }
</script>

<style scoped>

</style>