<template>
    <div class="dropdown show">

        <tabler-input-icon append="search">
            <b-form-input class="header-search"
                          placeholder="Zoeken..."
                          tabindex="1"
                          v-model="search"
                          type="search"
            />
        </tabler-input-icon>

        <div class="dropdown-menu dropdown-menu-arrow" :class="{show:isUsed}">


            <template v-if="hasPersons">

                <b-dropdown-header>Personen</b-dropdown-header>
                <b-dropdown-item v-for="person in persons.data"
                                 :key="person.id"
                                 :to="{name:'db.persons.view', params:{id:person.id}}"
                >
                    <b-form-row>
                        <div class="col-auto">
                            <base-avatar :image="person.avatar.image"
                                         :letters="person.avatar.letters"
                                         default-style="person-default"
                                         size="sm"
                            />
                        </div>
                        <b-col>
                            {{ person.name }}
                        </b-col>
                    </b-form-row>
                </b-dropdown-item>

            </template>

            <template v-if="hasGroups">
                <b-dropdown-header>Groepen</b-dropdown-header>

                <b-dropdown-item v-for="group in groups.data"
                                 :key="group.id"
                                 :to="{name:'db.groups.view', params:{id:group.id}}"
                >
                    <b-form-row>
                        <div class="col-auto">
                            <base-stamp :default-style="group.category.style" size="xs" />
                        </div>
                        <b-col>
                            {{ group.name }}
                        </b-col>
                    </b-form-row>
                </b-dropdown-item>
            </template>

            <template v-if="isLoading">
                <div class="text-muted font-italic p-2 text-center">Bezig met laden...</div>
            </template>

        </div>


    </div>
</template>

<script>
    import TablerInputIcon from "./TablerInputIcon";
    import gql from 'graphql-tag';
    import BaseAvatar from "./BaseAvatar";
    import TablerDimmer from "./TablerDimmer";
    import BaseStamp from "./BaseStamp";

    export default {
        components: {
            BaseStamp,
            TablerDimmer,
            BaseAvatar,
            TablerInputIcon
        },
        name: "the-global-search",

        apollo: {
            persons: {
                query: gql`
                    query searchPersons($search:String) {
                        persons(search:$search, limit:5) {
                            data {
                                id
                                name
                                avatar {
                                    image
                                    letters
                                }
                            }
                        }
                    }
                `,
                variables() {
                    return {
                        search:this.search
                    }
                },
                skip() {
                    return !this.isUsed;
                }

            },

            groups: {
                query: gql`
                    query searchGroups($search:String) {
                        groups(search:$search, limit:5) {
                            data {
                                id
                                name
                                category {
                                    id
                                    style
                                }
                            }
                        }
                    }
                `,
                variables() {
                    return {
                        search:this.search
                    }
                },
                skip() {
                    return !this.isUsed;
                }
            }
        },

        data() {
            return {
                search:"",
                persons:{data:[]},
                groups:{data:[]}
            }
        },

        computed: {

            hasPersons() {
                return this.persons.data.length > 0;
            },

            hasGroups() {
                return this.groups.data.length > 0;
            },

            isUsed() {
                return !!this.search;
            },

            isLoading() {
                return this.$apollo.queries.persons.loading && this.$apollo.queries.groups.loading;
            },

        },

        methods: {
            resetSearch() {
                this.search = "";
            }
        }
    }
</script>

<style scoped>

</style>