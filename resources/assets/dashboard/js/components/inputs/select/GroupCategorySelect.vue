<template>
    <vue-multiselect v-bind="multiselectProps" v-on="multiselectListeners">

        <template slot="option" slot-scope="{ option }">
            <base-stamp :default-style="option.style" size="xs" class="mr-2" />
            {{ option.name }}
            <small class="ml-2 option__description" v-if="size !== 'sm'">
                {{ option.description }}
            </small>
        </template>

        <template slot="singleLabel" slot-scope="{ option }">
            <div class="multiselect-flex-label">
                <div class="multiselect-flex-label-image">
                    <base-stamp :default-style="option.style" size="xs" />
                </div>
                <div class="multiselect-flex-label-name">{{ option.name }}</div>
                <div v-if="size !== 'sm'" class="multiselect-flex-label-extra">{{ option.description }}</div>
            </div>
        </template>

        <span class="multiselect__single multiselect__single_placeholder" slot="placeholder">
            Kies een Groepscategorie...
        </span>

    </vue-multiselect>
</template>

<script>
    import gql from "graphql-tag";
    import VueMultiselect from "vue-multiselect/src/Multiselect";
    import modelSelectMixin from "../../../mixins/modelSelectMixin";
    import BaseStamp from "../../displays/BaseStamp";


    export default {
        name: "group-category-select",

        components: {
            BaseStamp,
            VueMultiselect
        },

        mixins:[modelSelectMixin],

        modelSelect: {
            label:'name',
            queryKey:'groupCategories',
            query:{
                query:gql`
                    query getGroupCategoryOptions($limit:Int = 25, $search:String) {
                        groupCategories(limit:$limit, search:$search) {
                            data {
                                id
                                name
                                name_short
                                slug
                                description
                                style
                                is_required
                            }
                        }
                    }
                `,
            },
        },
    }
</script>

<style scoped>

</style>