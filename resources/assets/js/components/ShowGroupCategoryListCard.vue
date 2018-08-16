<template>

    <tabler-card no-body title="Groep-categoriëen" no-header>
        <template slot="options">
            <b-button size="sm" variant="success">Toevoegen</b-button>
        </template>

        <b-list-group class="card-list-group" @mouseleave="$emit('update:highlighted', null)">
            <show-group-category-list-card-item v-for="category in groupCategories.data"
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
    import { groupCategoryListQuery } from "../graphql/queries/groups.graphql";
    import TablerCard from "./TablerCard";
    import ShowGroupCategoryListCardItem from "./ShowGroupCategoryListCardItem";

    export default {
        components: {
            ShowGroupCategoryListCardItem,
            TablerCard
        },
        name: "show-group-category-list-card",

        apollo: {
            groupCategories: {
                query: groupCategoryListQuery
            }
        },

        model: {
            prop:'selected',
            event:'change'
        },

        props: {
            highlighted:Number,
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
                    from:0,
                    to:0,
                    total:0,
                    data:[]
                },

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