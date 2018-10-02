<template>
    <vue-multiselect v-bind="multiselectProps" v-on="multiselectListeners">

        <template slot="option" slot-scope="{ option }">
            <person-avatar :person="option" size="sm" />
            <span class="ml-1" v-if="option.name">
                {{ option.name.first }}
                <span v-if="option.name.nickname" style="opacity: 0.6">
                    [<span class="font-italic">{{ option.name.nickname }}</span>]
                </span>
                {{ option.name.prefix }}
                {{ option.name.last }}

            </span>
        </template>

        <template slot="singleLabel" slot-scope="{ option }">

            <div class="multiselect-flex-label">
                <div class="multiselect-flex-label-image">
                    <person-avatar :person="option" size="sm" />
                </div>
                <div class="multiselect-flex-label-name"><span-person-name :person-name="option.name" /></div>
                <div class="multiselect-flex-label-extra"><span-person-name :person-name="option.name" formal /></div>
            </div>
        </template>

        <template slot="tag" slot-scope="{ option, search, remove }">
            <base-tag class="mr-2 mb-2"
                      default-style="person-default"
                      :avatar="option.avatar"
                      @mousedown.prevent
                      remove-button
                      @remove-click="remove(option)"
            ><span-person-name :person-name="option.name" /></base-tag>
        </template>

        <span class="multiselect__single multiselect__single_placeholder" slot="placeholder">
            Kies een Persoon...
        </span>

    </vue-multiselect>
</template>

<script>
    import gql from 'graphql-tag';
    import VueMultiselect from "vue-multiselect/src/Multiselect";
    import modelSelectMixin from "../../../mixins/modelSelectMixin";
    import {selectPersonQuery} from "../../../apis/graphql/queries/select.graphql";
    import BaseTag from "../../displays/BaseTag";
    import PersonAvatar from "../../displays/PersonAvatar";
    import SpanPersonName from "../../displays/spans/SpanPersonName";

    export default {
        components: {
            SpanPersonName,
            PersonAvatar,
            BaseTag,
            VueMultiselect},
        name: "person-select",

        modelSelect: {
            queryKey:'persons',
            query:{
                query:gql`
                    query selectPersonQuery {
                        persons {
                            totalCount
                            edges {
                                node {
                                    id
                                    ...PersonAvatar
                                    name { ...SpanPersonName }
                                }
                            }
                        }
                    }
                    ${PersonAvatar.fragment}
                    ${SpanPersonName.fragment}
                `,
            },
        },

        mixins: [modelSelectMixin]
    }
</script>

<style scoped>

</style>