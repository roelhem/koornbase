<template>


    <div>

        <div class="form-group">
            <label for="create-app-form--name" class="form-label">Naam</label>
            <input id="create-app-form--name"  name="name" class="form-control state-invalid" type="text" v-model="values.name" required />
        </div>

        <div class="form-group">
            <label for="create-app-form--name_short" class="form-label">Korte Naam</label>
            <input id="create-app-form--name_short"  name="name_short" class="form-control" type="text" v-model="values.name_short" />
        </div>

        <div class="form-group">
            <label for="create-app-form--description" class="form-label">Beschrijving</label>
            <textarea class="form-control"
                      v-model="values.description"
                      name="description"
                      id="create-app-form--description"
            ></textarea>
        </div>

        <div>
            <button class="btn btn-primary" @click="createApp">Aanmaken</button>
        </div>

        <pre>{{ error }}</pre>

    </div>


</template>

<script>
    import FormInputName from "../FormInputName";
    import { createNewApp } from "../../mutations/apps.graphql";

    export default {
        components: {
            FormInputName
        },


        data:function () {
            return {
                values:{
                    name:null,
                    name_short:null,
                    description:null
                },
                error:null
            }
        },

        methods: {
            createApp() {

                const values = this.values;

                this.$apollo.mutate({
                    mutation: createNewApp,

                    variables: values,
                }).then(data => {
                    console.log(data);
                }).catch(error => {
                    this.error = error
                });

            }
        },


        name: "create-app-form"
    }
</script>

<style scoped>

</style>