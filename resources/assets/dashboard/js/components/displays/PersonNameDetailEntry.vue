<template>

    <subtile-detail-entry-flip-form label="Naam"
                                    :value="value"
                                    v-bind="$attrs"
                                    v-on="$listeners"
    >
        <span-person-name :person="person" full />

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
    import DetailEntry from "../layouts/cards/DetailEntry";
    import SpanPersonName from "./spans/SpanPersonName";
    import SubtileDetailEntryFlipForm from "../inputs/subtile/SubtileDetailEntryFlipForm";
    import InputGroupPersonName from "../inputs/groups/InputGroupPersonName";

    export default {
        name: "person-name-detail-entry",

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
                        name_first:null,
                        name_middle:null,
                        name_prefix:null,
                        name_last:null,
                        name_initials:null,
                        name_nickname:null,
                    }
                }
            }
        },

        computed: {
            value() {
                return {
                    name_first: this.person.name_first || null,
                    name_middle: this.person.name_middle || null,
                    name_prefix: this.person.name_prefix || null,
                    name_last: this.person.name_last || null,
                    name_initials: this.person.name_initials || null
                }
            }
        }
    }
</script>

<style scoped>

</style>