<template>
    <vue-multiselect v-bind="multiselectProps" v-on="multiselectListeners">

        <template slot="option" slot-scope="{ option }">
            <base-avatar v-bind="option.avatar" default-style="person-default" size="sm" style="vertical-align: middle" />
            <span class="ml-1">
                {{ option.name_first }}
                <span v-if="option.name_nickname" style="opacity: 0.6">
                    [<span class="font-italic">{{ option.name_nickname }}</span>]
                </span>
                {{ option.name_prefix }}
                {{ option.name_last }}

            </span>
        </template>

        <template slot="singleLabel" slot-scope="{ option }">
            <div style="width:100%; white-space:nowrap;">
                <base-avatar v-bind="option.avatar" default-style="person-default" size="sm" style="margin-bottom:-2px" />
                <span class="ml-1">{{ option.name }}</span>
                <small class="ml-2 text-muted">
                    {{ option.name_formal }}
                </small>
            </div>
        </template>

        <template slot="tag" slot-scope="{ option, search, remove }">
            <base-tag class="mr-2 mb-2"
                      :label="option.name"
                      default-style="person-default"
                      :avatar="option.avatar"
                      @mousedown.prevent
                      remove-button
                      @remove-click="remove(option)"
            />
        </template>

        <span class="multiselect__single multiselect__single_placeholder" slot="placeholder">
            Kies een Persoon...
        </span>

    </vue-multiselect>
</template>

<script>
    import VueMultiselect from "vue-multiselect/src/Multiselect";
    import modelSelectMixin from "../../../mixins/modelSelectMixin";
    import {selectPersonQuery} from "../../../apis/graphql/queries/select.graphql";
    import BaseAvatar from "../../displays/BaseAvatar";
    import BaseTag from "../../displays/BaseTag";

    export default {
        components: {
            BaseTag,
            BaseAvatar,
            VueMultiselect},
        name: "person-select",

        modelSelect: {
            queryKey:'persons',
            query:{
                query:selectPersonQuery
            },
        },

        mixins: [modelSelectMixin]
    }
</script>

<style scoped>

</style>