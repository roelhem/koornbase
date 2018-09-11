<template>
    <vue-multiselect v-bind="multiselectProps" v-on="multiselectListeners">

        <template slot="option" slot-scope="{ option }">
            <base-avatar v-bind="option.avatar" :default-style="option.person ? 'person-default': 'user-default'" size="sm" style="vertical-align: middle" />
            <span class="ml-1">{{ option.name_display }}</span>
            <span v-if="option.person !== null" class="font-italic" style="opacity: 0.4">[{{ option.name }}]</span>
            <small class="ml-2 option__description">
                {{ option.email }}
            </small>
        </template>

        <template slot="singleLabel" slot-scope="{ option }">
            <div style="width:100%; white-space:nowrap;">
                <base-avatar v-bind="option.avatar" :default-style="option.person ? 'person-default': 'user-default'" size="sm" style="margin-bottom:-2px" />
                <span class="ml-1">{{ option.name_display }}</span>
                <span v-if="option.person !== null" class="font-italic" style="opacity: 0.4">[{{ option.name }}]</span>
                <small class="ml-2 text-muted">
                    {{ option.email }}
                </small>
            </div>
        </template>

        <template slot="tag" slot-scope="{ option, search, remove }">
            <base-tag class="mr-2 mb-2"
                      :label="option.name"
                      default-style="users-default"
                      :avatar="option.avatar"
                      @mousedown.prevent
                      remove-button
                      @remove-click="remove(option)"
            />
        </template>

        <span class="multiselect__single multiselect__single_placeholder" slot="placeholder">
            Kies een Gebruiker...
        </span>

    </vue-multiselect>
</template>

<script>


    import VueMultiselect from "vue-multiselect/src/Multiselect";
    import {selectUserQuery} from "../../../apis/graphql/queries/select.graphql";
    import modelSelectMixin from "../../../mixins/modelSelectMixin";
    import BaseAvatar from "../../displays/BaseAvatar";
    import BaseTag from "../../displays/BaseTag";

    export default {
        components: {
            BaseTag,
            BaseAvatar,
            VueMultiselect},
        name: "user-select",

        modelSelect: {
            queryKey:'users',
            query:{
                query:selectUserQuery
            },
        },

        mixins:[modelSelectMixin],

    }
</script>

<style scoped>

</style>