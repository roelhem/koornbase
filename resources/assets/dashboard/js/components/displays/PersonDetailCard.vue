<template>

    <tabler-card :icon="icon" v-bind="$attrs" v-on="$listeners" no-body>


        <template slot="title">
            <slot name="title">{{ title }}</slot>

            <span v-if="person.name && person.name.first" class="text-muted mx-2">
                (<span-person-name :person-name="person.name" />)
            </span>

        </template>


        <detail-view in-card>

            <person-name-detail-entry :person="person" @submit="handleSubmitName" />

            <subtile-detail-entry-form label="Bijnaam"
                                       :value="person.name ? person.name.nickname : null"
                                       @submit="handleSubmitNickname"
            >
                <template v-if="person.name && person.name.nickname">
                    <base-field title="Bijnaam" name="nickname" v-if="person.name">{{ person.name.nickname }}</base-field>
                </template>
                <template v-else>
                    <base-field title="Korte naam" name="shortName" class="text-muted-dark" v-if="person.name">{{ person.name.first }}</base-field>
                    <small class="text-muted font-italic">(Geen bijnaam)</small>
                </template>
            </subtile-detail-entry-form>



            <subtile-detail-entry-date-form label="Geboortedatum"
                                            :value="person.birthDate"
                                            :max-date="maxBirthDate"
                                            @submit="handleSubmitBirthDate"
            >
                <span-birth-date v-if="person.birthDate" :birth-date="person.birthDate" />
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
                name { ...SpanPersonName }
                birthDate
                remarks
            }
            ${SpanPersonName.fragment}
        `,

        props: {
            person: {
                type:Object,
                required:true,
                default() {
                    return {
                        id:null,
                        name:{},
                        birthDate:null,
                        remarks:null
                    }
                }
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
                this.$apollo.mutate({
                    mutation:gql`
                        mutation updatePersonName($id:ID!, $firstName:String, $middleName:String, $initials:String, $prefixName:String, $lastName:String) {
                            updatePerson(id:$id, firstName:$firstName, middleName:$middleName, initials:$initials, prefixName:$prefixName, lastName:$lastName) {
                                id
                                name {
                                    first
                                    middle
                                    initials
                                    prefix
                                    last
                                }
                            }
                        }
                    `,
                    variables: { id, ...newValue },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updatePerson: {
                            __typename:'Person',
                            id,
                            name: {
                                __typename:'PersonName',
                                first: newValue.firstName,
                                middle: newValue.middleName,
                                initials: newValue.initials,
                                prefix: newValue.prefixName,
                                last: newValue.lastName,
                            }
                        }
                    }
                }).then(data => console.log(data));
            },

            handleSubmitNickname(newValue) {
                const id = this.person.id;
                const nickname = newValue;

                this.$apollo.mutate({
                    mutation:gql`
                        mutation updatePersonNickname($id:ID!, $nickname:String) {
                            updatePerson(id:$id, nickname:$nickname) {
                                id name { nickname }
                            }
                        }
                    `,
                    variables: { id, nickname },
                    optimisticResponse: {
                        __typename:'Mutation',
                        updatePerson: {
                            __typename:'Person',
                            id,
                            name: {
                                nickname,
                                __typename:'PersonName',
                            }
                        }
                    }
                }).then(data => console.log(data));
            },

            handleSubmitBirthDate(newValue) {
                const id = this.person.id;
                const birthDate = newValue;

                this.$apollo.mutate({
                    mutation:gql`
                        mutation updatePersonBirthDate($id:ID!, $birthDate:Date) {
                            updatePerson(id:$id, birthDate:$birthDate) {
                                id
                                birthDate
                            }
                        }
                    `,
                    variables: {id, birthDate},
                    optimisticResponse: {
                        __typename:'Mutation',
                        updatePerson: {
                            __typename:'Person',
                            id, birthDate
                        }
                    }
                }).then(data => console.log(data));
            }
        }
    }
</script>

<style scoped>

</style>