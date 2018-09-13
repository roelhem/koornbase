<template>
    <vue-multiselect v-bind="multiselectProps" v-on="multiselectListeners">

        <template slot="option" slot-scope="{ option }">
            <user-avatar :user="option" size="sm" />

            <span class="ml-1">
                <em>{{ option.name }}</em>
            </span>

            <small class="ml-2 option__description">
                {{ option.email }}
            </small>

            <span v-if="option.person" class="ml-2 option__description">
                <strong><span-person-name :person="option.person" with-nickname /></strong>
            </span>
        </template>

        <template slot="singleLabel" slot-scope="{ option }">
            <div class="multiselect-flex-label">
                <div class="multiselect-flex-label-image">
                    <user-avatar :user="option" size="sm" />
                </div>
                <div class="multiselect-flex-label-name">
                    {{ option.name }}
                </div>
                <div class="multiselect-flex-label-extra" style="font-size: 100%; padding-top:0px">
                    <template v-if="option.person">
                    <strong>Van:</strong> <span-person-name :person="option.person" /> &nbsp;&nbsp;&nbsp;&nbsp;
                    </template>
                    <strong>E-mail:</strong> {{ option.email }}
                </div>
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

    import gql from "graphql-tag";
    import VueMultiselect from "vue-multiselect/src/Multiselect";
    import modelSelectMixin from "../../../mixins/modelSelectMixin";
    import BaseTag from "../../displays/BaseTag";
    import UserAvatar from "../../displays/UserAvatar";
    import SpanPersonName from "../../displays/spans/SpanPersonName";

    export default {
        components: {
            SpanPersonName,
            UserAvatar,
            BaseTag,
            VueMultiselect},
        name: "user-select",

        modelSelect: {
            queryKey:'users',
            query:{
                query:gql`
                    query getUserOptions($limit:Int = 25, $search:String) {
                        users(limit:$limit, search:$search) {
                            data {
                                id
                                name
                                email
                                ...UserAvatar
                                person {
                                    id
                                    ...SpanPersonName
                                }
                            }
                        }
                    }
                    ${UserAvatar.fragment}
                    ${SpanPersonName.fragment}
                `,
            },
        },

        mixins:[modelSelectMixin],

    }
</script>

<style scoped>

</style>