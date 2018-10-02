<template>

    <tabler-card
            v-bind="$attrs"
            v-on="$listeners"
            header-class="pl-3"
            no-body
    >

        <template slot="header">
            <base-stamp :default-style="group.category.style" />
            <h3 class="card-title pl-3 w-100">
                <subtile-single-input-form :value="group.name" @submit="updateNameHandler" />
            </h3>
        </template>

        <subtile-card-body-form class="border-bottom"
                                :value="group.description"
                                placeholder="Geen omschrijving..."
                                @submit="updateDescriptionHandler"
        />

        <detail-view in-card>


            <subtile-detail-entry-form label="Korte naam" :value="group.shortName" @submit="updateNameShortHandler" />
            <subtile-detail-entry-form label="Persoons-titel" :value="group.memberName" @submit="updateMemberNameHandler" />

            <subtile-detail-entry-form label="Categorie"
                                       :value="group.category"
                                       @submit="updateCategoryHandler"
            >
                <span-group-category :category="group.category" />

                <group-category-select slot="input"
                                       slot-scope="{inputValue, inputCallback}"
                                       :value="inputValue"
                                       @input="inputCallback"
                                       size="sm"
                />
            </subtile-detail-entry-form>

            <detail-entry label="Tag">
                <group-tag :group="group" label="name" />
            </detail-entry>

        </detail-view>

    </tabler-card>

</template>

<script>
    import gql from 'graphql-tag';
    import TablerCard from "../layouts/cards/TablerCard";
    import BaseStamp from "./BaseStamp";
    import SubtileSingleInputForm from "../inputs/subtile/SubtileSingleInputForm";
    import SubtileCardBodyForm from "../inputs/subtile/SubtileCardBodyForm";
    import SubtileDetailEntryForm from "../inputs/subtile/SubtileDetailEntryForm";
    import SpanGroupCategory from "./spans/SpanGroupCategory";
    import DetailEntry from "../layouts/cards/DetailEntry";
    import DetailView from "../layouts/cards/DetailView";
    import GroupTag from "./GroupTag";
    import GroupCategorySelect from "../inputs/select/GroupCategorySelect";

    export default {
        name: "group-card",

        components: {
            GroupCategorySelect,
            GroupTag,
            DetailView,
            DetailEntry,
            SpanGroupCategory,
            SubtileDetailEntryForm,
            SubtileCardBodyForm,
            SubtileSingleInputForm,
            BaseStamp,
            TablerCard
        },

        fragment:gql`
            fragment GroupCard on Group {
                id
                slug
                name
                shortName
                memberName
                description
                category {
                    id
                    ...SpanGroupCategory
                }
            }
            ${SpanGroupCategory.fragment}
        `,

        props: {
            group: {
                type:Object,
                default:function() {
                    return {
                        name:null,
                        shortName:null,
                        memberName:null,
                        category:{}
                    }
                }
            }
        },

        methods: {



            updateNameHandler(newValue) {
                const id = this.group.id;
                const name = newValue;
                const name_short = this.group.name_short;
                const member_name = this.group.member_name;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation updateGroupName($id:ID!, $name:String) {
                            updateGroup(id:$id, name:$name) {
                                id name name_short member_name
                            }
                        }
                    `,
                    variables: { id, name },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id, name, name_short, member_name
                        }
                    }
                }).then(data => console.log(data)).catch(error => console.log(error));
            },

            updateNameShortHandler(newValue) {
                const id = this.group.id;
                const name_short = newValue;
                const member_name = this.group.member_name;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation updateGroupNameShort($id:ID!, $name_short:String) {
                            updateGroup(id:$id, name_short:$name_short) {
                                id name_short member_name
                            }
                        }
                    `,
                    variables: { id, name_short },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id, name_short, member_name
                        }
                    }
                }).then(data => console.log(data)).catch(error => console.log(error));
            },

            updateMemberNameHandler(newValue) {
                const id = this.group.id;
                const member_name = newValue;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation updateGroupMemberName($id:ID!, $member_name:String) {
                            updateGroup(id:$id, member_name:$member_name) {
                                id member_name
                            }
                        }
                    `,
                    variables: { id, member_name },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id, member_name
                        }
                    }
                }).then(data => console.log(data)).catch(error => console.log(error));
            },

            updateDescriptionHandler(newValue) {
                const id = this.group.id;
                const description = newValue;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation updateGroupDescription($id:ID!, $description:String) {
                            updateGroup(id:$id, description:$description) {
                                id description
                            }
                        }
                    `,
                    variables: {id, description},

                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id,
                            description,
                        }
                    }
                }).then(data => console.log(data)).catch(error => console.log(error))
            },

            updateCategoryHandler(newValue) {
                const id = this.group.id;
                const category = Object.assign({__typename:"GroupCategory"}, newValue);
                const category_id = category.id;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation updateGroupGroupCategory($id:ID!, $category_id:ID!) {
                            updateGroup(id:$id, category_id:$category_id) {
                                id
                                category {
                                    id
                                    name
                                    name_short
                                    description
                                    style
                                }
                            }
                        }
                    `,
                    variables:{id, category_id},

                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroup: {
                            __typename:'Group',
                            id,
                            category,
                        }
                    }

                }).then(data => console.log(data));
            }

        }
    }
</script>

<style scoped>

</style>