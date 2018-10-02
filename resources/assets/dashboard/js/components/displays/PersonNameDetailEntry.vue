<template>

    <subtile-detail-entry-flip-form label="Naam"
                                    :value="value"
                                    v-bind="$attrs"
                                    v-on="$listeners"
    >
        <span-person-name :person-name="person.name" full />

        <template slot="form" slot-scope="{ inputValue, inputCallback }">

            <template v-if="inputValue">
                <input-group-person-name :id="`person-${person.id}-name-detail-entry-flip-form-input`"
                                         :value="inputValue"
                                         @input="inputCallback"
                />
            </template>
        </template>

    </subtile-detail-entry-flip-form>

</template>

<script>
    import gql from "graphql-tag";

    import DetailEntry from "../layouts/cards/DetailEntry";
    import SpanPersonName from "./spans/SpanPersonName";
    import SubtileDetailEntryFlipForm from "../inputs/subtile/SubtileDetailEntryFlipForm";
    import InputGroupPersonName from "../inputs/groups/InputGroupPersonName";

    export default {
        name: "person-name-detail-entry",

        fragment: gql`
            fragment PersonNameDetailEntry on Person {
                id
                name { ...SpanPersonName }
            }
            ${SpanPersonName.fragment}
        `,

        components: {
            InputGroupPersonName,
            SubtileDetailEntryFlipForm,
            SpanPersonName,
            DetailEntry
        },

        data() {
            return {
            }
        },

        props: {
            person: {
                type:Object,
                required:true,
                default() {
                    return {
                        name: {}
                    }
                }
            }
        },

        computed: {
            value() {
                if(this.person.name) {
                    return {
                        name_first: this.person.name.first || null,
                        name_middle: this.person.name.middle || null,
                        name_prefix: this.person.name.prefix || null,
                        name_last: this.person.name.last || null,
                        name_initials: this.person.name.initials || null
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>