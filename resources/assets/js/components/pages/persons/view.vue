<template>

    <div>

        <tabler-banner />

        <!-- HEADER -->
        <b-container style="margin-top: -40px">
            <div class="d-flex">
                <div class="px-2">
                    <tabler-dimmer :active="$apollo.queries.person.loading">
                    <base-avatar v-bind="person.avatar"
                                 size="xxl"
                                 default-style="person-default" />
                    </tabler-dimmer>
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
                        <kb-group-tag v-for="group in person.groups.data"
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
                <b-nav-item :to="{name: 'db.persons.view.overview', params:{id: id}}">Overzicht</b-nav-item>
                <b-nav-item :to="{name: 'db.persons.view.contact', params:{id: id}}">Contactgegevens</b-nav-item>
                <b-nav-item :to="{name: 'db.persons.view.membership', params:{id: id}}">Lidmaatschap</b-nav-item>
                <b-nav-item :to="{name: 'db.persons.view.debug', params:{id: id}}">Debug</b-nav-item>



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
            <router-view :person-id="id" />
        </b-container>


    </div>

</template>

<script>
    import TablerBanner from "../../TablerBanner";
    import BaseAvatar from "../../BaseAvatar";
    import DataDisplay from "../../displays/data-display";
    import KbGroupTag from "../../KbGroupTag";
    import BaseIcon from "../../BaseIcon";
    import TablerDimmer from "../../TablerDimmer";

    import { personsView } from "../../../graphql/dashboard.graphql";

    export default {
        components: {
            TablerDimmer,
            BaseIcon,
            DataDisplay,
            BaseAvatar,
            TablerBanner,
            KbGroupTag
        },
        name: "page-person",

        props: {
            id: [String, Number]
        },

        apollo: {
            person: {
                query: personsView,
                variables() {
                    return {
                        id:this.id
                    }
                }

            },
        },

        data: function() {
            return {
                person: {}
            }
        },
    }
</script>

<style scoped>

</style>