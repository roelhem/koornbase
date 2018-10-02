<template>
    <vue-multiselect v-bind="multiselectProps" v-on="multiselectListeners">

        <template slot="option" slot-scope="{ option }">
            <base-icon icon="certificate" from="fa" class="mx-2 text-muted" />
            {{ option.name }}
            <small class="ml-2 option__description" v-if="size !== 'sm'">
                {{ option.description }}
            </small>
        </template>

        <template slot="singleLabel" slot-scope="{ option }">
            <div class="multiselect-flex-label">
                <div class="multiselect-flex-label-image">
                    <base-icon class="text-muted" icon="certificate" from="fa" />
                </div>
                <div class="multiselect-flex-label-name">{{ option.name }}</div>
                <div v-if="size !== 'sm'" class="multiselect-flex-label-extra">{{ option.description }}</div>
            </div>
        </template>

        <template slot="tag" slot-scope="{ option, search, remove }">
            <certificate-category-tag :certificate-category="option"
                                      class="mr-2 mb-2"
                                      remove-button
                                      @remove-click="remove(option)"
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
    import BaseIcon from "../../displays/BaseIcon";
    import CertificateCategoryTag from "../../displays/CertificateCategoryTag";


    export default {
        name: "certificate-category-select",

        components: {
            CertificateCategoryTag,
            BaseIcon,
            VueMultiselect
        },

        mixins:[modelSelectMixin],

        modelSelect: {
            label:'name',
            queryKey:'certificateCategories',
            query:{
                query:gql`
                    query getGroupCategoryOptions {
                        certificateCategories(first:50) {
                            edges {
                                node {
                                    id
                                    name
                                    description
                                    ...CertificateCategoryTag
                                }
                            }
                        }
                    }
                    ${CertificateCategoryTag.fragment}
                `,
            },
        },
    }
</script>

<style scoped>

</style>