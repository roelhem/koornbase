<template>

    <tabler-card
        status="azure"
        status-left
        no-body
        collapsible
        :collapsed="collapsed"
        v-on:update:collapsed="e => $emit('update:collapsed', e)"
        collapsibleWithHeader
        icon="user"
        icon_from="fe"
        :is-loading="!loaded"
    >

        <template slot="title">
            Persoonsgegevens
            <span class="text-muted mx-2" v-if="loaded">
                ( <data-display title="Naam">{{ person.name }}</data-display> )
            </span>
        </template>

        <detail-view in-card>
            <detail-entry label="Naam">
                <display-person-name
                        v-if="loaded"
                        v-bind="person"
                />
            </detail-entry>

            <detail-entry label="Bijnaam">
                <template v-if="loaded">
                    <template v-if="person.name_nickname">
                        <data-display title="Bijnaam">{{ person.name_nickname }}</data-display>
                    </template>
                    <template v-else>
                        <data-display title="Korte naam" class="text-muted-dark font-italic">
                            {{ person.name_short }}
                        </data-display>
                        <small class="text-muted font-italic">(geen bijnaam)</small>
                    </template>
                </template>
            </detail-entry>

            <detail-entry label="Geboortedatum">
                <display-person-birth-date v-if="loaded"
                                           :birth_date="person.birth_date" />
            </detail-entry>

        </detail-view>

    </tabler-card>

</template>

<script>
    import TablerCard from "../layouts/cards/TablerCard";
    import DataDisplay from "./DataDisplay";
    import DetailView from "../layouts/cards/DetailView";
    import DetailEntry from "../layouts/cards/DetailEntry";
    import DisplayPersonName from "./DisplayPersonName";
    import DisplayPersonBirthDate from "./DisplayPersonBirthDate";

    export default {

        name: "kb-person-detail-card",
        components: {
            DataDisplay,
            TablerCard,
            DetailView,
            DetailEntry,
            DisplayPersonName,
            DisplayPersonBirthDate
        },

        props: {

            person:{
                type:Object,
                default:function() {
                    return null;
                }
            },

            collapsed:{
                type:Boolean,
                default:false
            },

        },

        computed: {
            loaded() {
                return !!this.person;
            }
        }
    }
</script>

<style scoped>

</style>