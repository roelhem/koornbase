<template>
    <vue-multiselect v-bind="multiselectProps" v-on="multiselectListeners">

        <template slot="option" slot-scope="{ option }">
            {{ option.name }}
            <small class="ml-2 option__description" v-if="size !== 'sm'">
                {{ option.description }}
            </small>
        </template>

        <template slot="singleLabel" slot-scope="{ option }">
            <div style="width:100%; overflow:hidden; white-space:nowrap;">
                <span>{{ option.name }}</span>
                <small v-if="option.description" class="ml-2 text-muted">
                    {{ option.description }}
                </small>
            </div>
        </template>

        <template slot="tag" slot-scope="{ option, search, remove }">
            <base-tag class="mr-2 mb-2"
                      :label="option.name_short"
                      :default-style="option.category.style"
                      @mousedown.prevent
                      remove-button
                      @remove-click="remove(option)"
            />
        </template>

        <span class="multiselect__single multiselect__single_placeholder" slot="placeholder">
            Kies een Groep...
        </span>

    </vue-multiselect>
</template>

<script>
    import VueMultiselect from "vue-multiselect/src/Multiselect";
    import modelSelectMixin from "../../../mixins/modelSelectMixin";
    import {selectGroupQuery} from "../../../apis/graphql/queries/select.graphql";
    import BaseAvatar from "../../displays/BaseAvatar";
    import BaseTag from "../../displays/BaseTag";

    export default {
        components: {
            BaseTag,
            BaseAvatar,
            VueMultiselect},
        name: "group-select",

        modelSelect: {
            queryKey:'groups',
            query:{
                query:selectGroupQuery
            },
        },

        mixins: [modelSelectMixin]
    }
</script>

<style scoped>

</style>