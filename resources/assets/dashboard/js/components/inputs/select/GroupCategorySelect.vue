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

        <template slot="tag" slot-scope="{ option, search, remove }">
            <base-tag class="mr-2 mb-2"
                      :default-style="option.style"
                      @mousedown.prevent
                      remove-button
                      @remove-click="remove(option)"
                      :label="option.shortName"
                      rounded
            />
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
    import BaseTag from "../../displays/BaseTag";


    export default {
        name: "group-category-select",

        components: {
            BaseTag,
            BaseStamp,
            VueMultiselect
        },

        mixins:[modelSelectMixin],

        modelSelect: {
            label:'name',
            queryKey:'groupCategories',
            query:{
                query:gql`
                    query getGroupCategoryOptions {
                        groupCategories(first:50) {
                            edges {
                                node {
                                    id
                                    name
                                    shortName
                                    description
                                    style
                                }
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