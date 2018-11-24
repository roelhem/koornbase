<template>

    <tabler-card no-body title="Groep-categoriëen" no-header>
        <template slot="options">
            <b-button size="sm" variant="success">Toevoegen</b-button>
        </template>

        <b-list-group class="card-list-group" @mouseleave="$emit('update:highlighted', null)">
            <show-group-category-list-card-item v-for="category in items"
                                                :key="category.id"
                                                :category="category"
                                                :default-style="category.style"
                                                :highlighted="highlighted === category.id"
                                                :active="getCategoryActive(category.id)"
                                                @mouseover="$emit('update:highlighted', category.id)"
                                                @activate="handleActivate"
                                                @deactivate="handleDeactivate"
            />
        </b-list-group>
    </tabler-card>

</template>

<script>
    import gql from "graphql-tag";
    import TablerCard from "../layouts/cards/TablerCard";
    import ShowGroupCategoryListCardItem from "./ShowGroupCategoryListCardItem";

    export default {
        components: {
            ShowGroupCategoryListCardItem,
            TablerCard
        },
        name: "show-group-category-list-card",

        apollo: {
            groupCategories: {
                query: gql`
                    query groupCategoryList {
                        groupCategories {
                            edges {
                                node {
                                    id
                                    style
                                    ...ShowGroupCategoryListCardItem
                                }
                            }
                        }
                    }
                    ${ShowGroupCategoryListCardItem.fragment}
                `,
            }
        },

        model: {
            prop:'selected',
            event:'change'
        },

        props: {
            highlighted:[Number, String],
            selected:{
                type:Array,
                default:function () {
                    return [];
                }
            },
        },

        data: function() {
            return {
                groupCategories:{
                    edges:[]
                },

            }
        },

        computed: {
            items() {
                return this.groupCategories.edges.map(edge => edge.node);
            }
        },

        methods: {

            getCategoryActive(categoryId) {
                return this.selected.indexOf(categoryId) !== -1;
            },

            handleActivate(categoryId) {
                if(!this.getCategoryActive(categoryId)) {
                    let res = this.selected.slice(0);
                    res.push(categoryId);
                    this.$emit('change',res);
                }
            },

            handleDeactivate(categoryId) {
                const index = this.selected.indexOf(categoryId);
                if(index > -1) {
                    let res = this.selected.slice(0);
                    res.splice(index, 1);
                    this.$emit('change', res);
                }
            }
        }
    }
</script>

<style scoped>

</style>