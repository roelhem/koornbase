<template>

    <div>

        <tabler-banner />

        <!-- HEADER -->
        <b-container style="margin-top: -40px">
            <div class="d-flex">
                <div class="px-2">
                    <tabler-dimmer :active="$apollo.queries.person.loading">
                        <person-avatar :person="person" size="xxl" />
                    </tabler-dimmer>
                </div>

                <div class="p-1 pt-3">
                    <h1 class="m-1">
                        <span-person-name :person-name="person.name" with-nickname />
                    </h1>
                    <group-tag-list :groups="person.groups.edges.map(edge => edge.node)" label="member_name" />
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

    import gql from "graphql-tag";

    import TablerBanner from "../../components/layouts/title/TablerBanner";
    import DataDisplay from "../../components/displays/DataDisplay";
    import GroupTag from "../../components/displays/GroupTag";
    import BaseIcon from "../../components/displays/BaseIcon";
    import TablerDimmer from "../../components/layouts/cards/TablerDimmer";
    import SpanPersonName from "../../components/displays/spans/SpanPersonName";
    import GroupTagList from "../../components/displays/GroupTagList";
    import PersonAvatar from "../../components/displays/PersonAvatar";

    export default {
        components: {
            PersonAvatar,
            GroupTagList,
            SpanPersonName,
            TablerDimmer,
            BaseIcon,
            DataDisplay,
            TablerBanner,
            GroupTag
        },
        name: "page-person",

        props: {
            id: [String, Number]
        },

        apollo: {
            person: {
                query: gql`
                    query viewPerson($id:ID!) {
                        person(id:$id) {
                            id
                            ...PersonAvatar
                            name {...SpanPersonName}
                            groups(first:20) {
                                edges {
                                    node {
                                        id
                                        ...GroupTag
                                    }
                                }
                            }
                        }
                    }
                    ${PersonAvatar.fragment}
                    ${SpanPersonName.fragment}
                    ${GroupTag.fragment}
                `,
                variables() {
                    return {
                        id:this.id
                    }
                }

            },
        },

        data: function() {
            return {
                person: {
                    groups:{edges:[]}
                }
            }
        },
    }
</script>

<style scoped>

</style>