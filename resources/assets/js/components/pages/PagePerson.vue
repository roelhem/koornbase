<template>

    <div>

        <tabler-banner />

        <!-- HEADER -->
        <b-container style="margin-top: -40px">
            <div class="d-flex">
                <div class="px-2">
                    <base-avatar v-bind="person.avatar"
                                 size="xxl"
                                 default-style="person-default" />
                </div>

                <div class="p-1 pt-3">
                    <h1 class="m-1">
                        <span>{{ person.name_first }}</span>
                        <span class="small text-muted" v-if="person.name_nickname">
                            [
                            <span class="font-italic text-muted-dark">{{ person.name_nickname }}</span>
                            ]
                        </span>
                        <span>{{ person.name_prefix }} {{ person.name_last }}</span>
                    </h1>
                    <div class="tags">
                        <kb-group-tag v-for="group in person.groups"
                                      :key="group.slug"
                                      :group="group"
                                      label="member_name" />
                    </div>
                </div>
            </div>
        </b-container>

        <!-- NAVIGATION -->
        <b-container class="my-4">
            <b-nav tabs>
                <b-nav-item :to="{name: 'persons.view.overview', params:{id: id}}">Overzicht</b-nav-item>
                <b-nav-item :to="{name: 'persons.view.contact', params:{id: id}}">Contactgegevens</b-nav-item>
                <b-nav-item :to="{name: 'persons.view.debug', params:{id: id}}">Debug</b-nav-item>



                <div class="ml-auto px-4 py-2">

                    <b-dropdown id="person_options_dropdown"
                                text="Opties"
                                variant="secondary"
                                right>
                        <b-dropdown-item>Lidmaatschap toevoegen</b-dropdown-item>
                        <b-dropdown-item>Account aanmaken</b-dropdown-item>
                        <b-dropdown-divider />
                        <b-dropdown-item>Verwijderen</b-dropdown-item>
                    </b-dropdown>

                </div>
            </b-nav>
        </b-container>

        <b-container>
            <router-view :person="person" />
        </b-container>


    </div>

</template>

<script>
    import axios from 'axios';
    import TablerBanner from "../TablerBanner";
    import BaseAvatar from "../BaseAvatar";
    import DataDisplay from "../displays/data-display";
    import KbGroupTag from "../KbGroupTag";
    import BaseIcon from "../BaseIcon";

    export default {
        components: {
            BaseIcon,
            DataDisplay,
            BaseAvatar,
            TablerBanner,
            KbGroupTag
        },
        name: "page-person",

        props: {
            id: String
        },

        data: function() {
            return {
                isLoading: true,
                person: {}
            }
        },

        watch: {
            '$route':'loadData'
        },

        created() {
            this.loadData();
        },

        methods: {
            loadData() {
                axios.get('/api/persons/'+this.id, {
                    params: {
                        with: [
                            'groups',
                            'address',
                            'emailAddress',
                            'emailAddresses',
                            'phoneNumber',
                            'activeCards'
                        ],
                        fields: ['avatar','style','membership_status']
                    }
                }).then(result => {
                    this.person = result.data.data;
                    this.isLoading = false;
                }).catch(e => { console.log(e) });
            }
        }
    }
</script>

<style scoped>

</style>