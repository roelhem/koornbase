<template>

    <b-container>

        <b-row>
            <b-col>
                <h1>HI</h1>
            </b-col>
        </b-row>

        <pre>
            {{ apps }}
        </pre>
    </b-container>

</template>

<script>
    import gql from 'graphql-tag';

    export default {

        apollo: {
            apps: gql`{
                apps {
                    from
                    to
                    total
                    data {
                        id
                        name
                        name_short
                        description
                    }
                }
            }`,
        },

        data:function() {
            return {
                apps:[]
            }
        },
    }
</script>

<style scoped>

</style>