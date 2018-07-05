<template>

    <b-container>

        <h1>Mijn Account</h1>

        <b-row>


            <b-col lg="4">

                <b-card>
                    <b-media>
                        <base-avatar slot="aside" size="xxl" class="mr-5" color="blue" v-bind="user.avatar" />
                        <h4 class="m-0">{{ user.name_display }}</h4>
                        <p class="text-muted mb-0">
                            {{user.email}}
                            <small class="text-muted-dark">(<em>{{ user.name }}</em>)</small>
                        </p>
                    </b-media>
                </b-card>

                <b-list-group>
                    <b-list-group-item to="/me/personal">Mijn Persoonsgegevens</b-list-group-item>
                    <b-list-group-item to="/me/kb">Mijn Koornbeurs</b-list-group-item>
                </b-list-group>

            </b-col>


            <b-col lg="8">
                <router-view />

            </b-col>


        </b-row>

    </b-container>

</template>

<script>
    import DataDisplay from "../displays/data-display";
    import BaseAvatar from "../BaseAvatar";
    import PageMePersonalData from "./PageMePersonalData";
    import { mapState } from "vuex";

    export default {

        components: {
            PageMePersonalData,
            BaseAvatar,
            DataDisplay
        },

        computed: {
            ...mapState({
                user: state => state.currentUser
            })
        },

        name: "page-me",
    }
</script>
