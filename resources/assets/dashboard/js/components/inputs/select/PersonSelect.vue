<template>
    <vue-multiselect v-bind="multiselectProps" v-on="multiselectListeners">

        <template slot="option" slot-scope="{ option }">
            <person-avatar :person="option" size="sm" />
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

            <div class="multiselect-flex-label">
                <div class="multiselect-flex-label-image">
                    <person-avatar :person="option" size="sm" />
                </div>
                <div class="multiselect-flex-label-name">{{ option.name }}</div>
                <div class="multiselect-flex-label-extra">{{ option.name_formal }}</div>
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
    import BaseTag from "../../displays/BaseTag";
    import PersonAvatar from "../../displays/PersonAvatar";

    export default {
        components: {
            PersonAvatar,
            BaseTag,
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