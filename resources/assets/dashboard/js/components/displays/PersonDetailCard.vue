<template>

    <tabler-card :icon="icon" v-bind="$attrs" v-on="$listeners" no-body>


        <template slot="title">
            <slot name="title">{{ title }}</slot>

            <span v-if="person.name_first" class="text-muted mx-2">
                (<span-person-name :person="person" />)
            </span>

        </template>


        <detail-view in-card>

            <person-name-detail-entry :person="person" @submit="handleSubmitName" />

            <subtile-detail-entry-form label="Bijnaam"
                                       :value="person.name_nickname"
                                       @submit="handleSubmitNickname"
            >
                <template v-if="person.name_nickname">
                    <base-field title="Bijnaam" name="name_nickname">{{ person.name_nickname }}</base-field>
                </template>
                <template v-else>
                    <base-field title="Korte naam" name="name_short" class="text-muted-dark">{{ person.name_short }}</base-field>
                    <small class="text-muted font-italic">(Geen bijnaam)</small>
                </template>
            </subtile-detail-entry-form>



            <subtile-detail-entry-date-form label="Geboortedatum"
                                            :value="person.birth_date"
                                            :max-date="maxBirthDate"
                                            @submit="handleSubmitBirthDate"
            >
                <span-birth-date v-if="person.birth_date" :birth_date="person.birth_date" />
                <small class="text-muted font-italic" v-else>(Onbekend)</small>
            </subtile-detail-entry-date-form>



        </detail-view>

        <subtile-card-body-form class="border-top"
                                placeholder="Geen opmerkingen voor deze persoon..."
                                :value="person.remarks"
                                @submit="handleSubmitRemarks"
        />

        <template v-if="$slots.footer" slot="footer"><slot name="footer" /></template>

    </tabler-card>

</template>

<script>
    import gql from "graphql-tag";
    import TablerCard from "../layouts/cards/TablerCard";
    import DetailView from "../layouts/cards/DetailView";
    import DetailEntry from "../layouts/cards/DetailEntry";
    import SpanPersonName from "./spans/SpanPersonName";
    import BaseField from "./BaseField";
    import SpanBirthDate from "./spans/SpanBirthDate";
    import SubtileCardBodyForm from "../inputs/subtile/SubtileCardBodyForm";
    import SubtileDetailEntryForm from "../inputs/subtile/SubtileDetailEntryForm";
    import SubtileDetailEntryDateForm from "../inputs/subtile/SubtileDetailEntryDateForm";
    import PersonNameDetailEntry from "./PersonNameDetailEntry";

    export default {
        name: "person-detail-card",

        components: {
            PersonNameDetailEntry,
            SubtileDetailEntryDateForm,
            SubtileDetailEntryForm,
            SubtileCardBodyForm,
            SpanBirthDate,
            BaseField,
            SpanPersonName,
            DetailEntry,
            DetailView,
            TablerCard
        },

        fragment: gql`
            fragment PersonDetailCard on Person {
                id
                name_short
                birth_date
                ...SpanPersonName
                remarks
            }
            ${SpanPersonName.fragment}
        `,

        props: {
            person: {
                type:Object,
                required:true,
            },

            icon: {
                default:"user"
            },

            title: {
                type:String,
                default:"Persoonsgegevens"
            }
        },

        computed: {
            maxBirthDate() {
                return new Date();
            }
        },

        methods: {
            handleSubmitRemarks(newValue) {
                const id = this.person.id;
                const remarks = newValue;

                this.$apollo.mutate({
                    mutation:gql`
                        mutation updatePersonRemarks($id:ID!, $remarks:String) {
                            updatePerson(id:$id, remarks:$remarks) {
                                id remarks
                            }
                        }
                    `,
                    variables: { id, remarks },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updatePerson: {
                            __typename:'Person',
                            id, remarks
                        }
                    }
                }).then(data => console.log(data));
            },

            handleSubmitName(newValue) {
                const id = this.person.id;
                const name_first = newValue.name_first;
                const name_middle = newValue.name_middle;
                const name_initials = newValue.name_initials;
                const name_prefix = newValue.name_prefix;
                const name_last = newValue.name_last;

                const name_nickname = this.person.name_nickname;
                const name_short = name_nickname ? name_nickname : name_first;

                this.$apollo.mutate({
                    mutation:gql`
                        mutation updatePersonName($id:ID!, $name_first:String, $name_middle:String, $name_initials:String, $name_prefix:String, $name_last:String) {
                            updatePerson(id:$id, name_first:$name_first, name_middle:$name_middle, name_initials:$name_initials, name_prefix:$name_prefix, name_last:$name_last) {
                                id name_first name_middle name_initials name_prefix name_last
                                name_short
                            }
                        }
                    `,
                    variables: { id, name_first, name_middle, name_initials, name_prefix, name_last },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updatePerson: {
                            __typename:'Person',
                            id, name_first, name_middle, name_initials, name_prefix, name_last,
                            name_short
                        }
                    }
                }).then(data => console.log(data));
            },

            handleSubmitNickname(newValue) {
                const id = this.person.id;
                const name_nickname = newValue;

                const name_short = name_nickname ? name_nickname : this.person.name_first;

                this.$apollo.mutate({
                    mutation:gql`
                        mutation updatePersonNickname($id:ID!, $name_nickname:String) {
                            updatePerson(id:$id, name_nickname:$name_nickname) {
                                id name_nickname name_short
                            }
                        }
                    `,
                    variables: { id, name_nickname },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updatePerson: {
                            __typename:'Person',
                            id, name_nickname, name_short
                        }
                    }
                }).then(data => console.log(data));
            },

            handleSubmitBirthDate(newValue) {
                const id = this.person.id;
                const birth_date = newValue;

                this.$apollo.mutate({
                    mutation:gql`
                        mutation updatePersonBirthDate($id:ID!, $birth_date:Date) {
                            updatePerson(id:$id, birth_date:$birth_date) {
                                id
                                birth_date
                            }
                        }
                    `,
                    variables: {id, birth_date},
                    optimisticResponse: {
                        __typename:'Mutation',
                        updatePerson: {
                            __typename:'Person',
                            id, birth_date
                        }
                    }
                }).then(data => console.log(data));
            }
        }
    }
</script>

<style scoped>

</style>