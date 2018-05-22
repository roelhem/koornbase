<template>

    <b-container>
        <b-row>
            <b-col>
                <b-card no-body>
                    <b-table class="card-table" :items="groupProvider"></b-table>
                </b-card>
            </b-col>
        </b-row>
    </b-container>

</template>

<script>
    import axios from 'axios';

    export default {
        name: "page",

        methods: {
            groupProvider(ctx) {
                let promise = axios.get('/people/groups/search', {
                    params: {}
                });

                return promise.then(response => {
                    const items = response.data.data;
                    return(items);
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>

<style scoped>

</style>