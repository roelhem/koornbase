<template>
    <vue-multiselect v-bind="multiselectProps" v-on="multiselectListeners">

        <template slot="option" slot-scope="{ option }">
            <base-stamp :default-style="option.category.style" size="xs" class="mr-2" />
            {{ option.name }}
            <small class="ml-2 option__description" v-if="size !== 'sm'">
                {{ option.description }}
            </small>
        </template>

        <template slot="singleLabel" slot-scope="{ option }">
            <div class="multiselect-flex-label">
                <div class="multiselect-flex-label-image">
                    <base-stamp :default-style="option.category.style" size="xs" />
                </div>
                <div class="multiselect-flex-label-name">{{ option.name }}</div>
                <div class="multiselect-flex-label-extra">{{ option.description }}</div>
            </div>

        </template>

        <template slot="tag" slot-scope="{ option, search, remove }">
            <group-tag class="mr-2 mb-2" :group="option"
                       @mousedown.prevent
                       remove-button
                       @remove-click="remove(option)"
                       :label="tagLabel"
            />
        </template>

        <span class="multiselect__single multiselect__single_placeholder" slot="placeholder">
            Kies een Groep...
        </span>

    </vue-multiselect>
</template>

<script>
    import gql from "graphql-tag";
    import VueMultiselect from "vue-multiselect/src/Multiselect";
    import modelSelectMixin from "../../../mixins/modelSelectMixin";
    import {selectGroupQuery} from "../../../apis/graphql/queries/select.graphql";
    import BaseAvatar from "../../displays/BaseAvatar";
    import GroupTag from "../../displays/GroupTag";
    import BaseStamp from "../../displays/BaseStamp";

    export default {
        components: {
            BaseStamp,
            GroupTag,
            BaseAvatar,
            VueMultiselect
        },
        name: "group-select",

        modelSelect: {
            queryKey:'groups',
            query:{
                query:gql`
                    query selectGroupQuery {
                        groups {
                            edges {
                                node {
                                    id
                                    name
                                    description
                                    category {
                                        id
                                        name
                                        style
                                    }
                                    ...GroupTag
                                }
                            }
                        }
                    }
                    ${GroupTag.fragment}
                `,
            },
        },

        props: {
            tagLabel:{
                type:String,
                default:"shortName"
            }
        },

        mixins: [modelSelectMixin]
    }
</script>

<style scoped>

</style>