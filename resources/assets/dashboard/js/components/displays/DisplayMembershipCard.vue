<template>
    <tabler-card :status="status | membershipStatusColorName "
                 no-body
                 v-bind="$attrs"
                 v-on="$listeners"
                 :collapsible="!editMode"
                 :collapsible-with-header="!editMode"
                 :collapsed.sync="collapsed"
    >

        <template slot="title">
            Lidmaatschap
            <template v-if="collapsed">
                <span class="small">
                    <template v-if="status === 'FORMER_MEMBER'">
                        <span v-if="hasStart" class="text-muted font-italic">van</span>
                        <span v-if="hasStart" class="text-muted-dark">{{ startMoment | date('sm') }}</span>
                        <span class="text-muted font-italic">tot</span>
                        <span class="text-muted-dark">{{ endMoment | date('sm') }}</span>
                    </template>
                    <template v-else-if="status === 'MEMBER'">
                        <span class="text-muted font-italic">vanaf</span>
                        <span class="text-muted-dark">{{ startMoment | date('sm') }}</span>
                    </template>
                    <template v-else-if="status === 'NOVICE'">
                        <span class="text-muted font-italic">ingeschreven op</span>
                        <span class="text-muted-dark">{{ applicationMoment | date('sm') }}</span>
                    </template>
                </span>
            </template>
        </template>

        <subtile-card-body-form placeholder="Geen opmerkingen..."
                                :value="remarks"
                                @submit="handleSubmitRemarks"
        />

        <div class="card-body">
            <template v-if="editMode">


                <tabler-form-group label="Datum van inschrijving"
                                   horizontal
                                   label-cols="5"
                >
                    <v-date-picker mode="single"
                                   v-model="newValues.application"
                                   :min-date="null"
                                   :max-date="newValues.start || newValues.end"
                    >
                        <date-picker-input slot-scope="sc" v-bind="sc" />
                    </v-date-picker>
                </tabler-form-group>

                <tabler-form-group label="Datum van inauguratie"
                                   horizontal
                                   label-cols="5"
                >
                    <v-date-picker mode="single"
                                   v-model="newValues.start"
                                   :min-date="newValues.application"
                                   :max-date="newValues.end"
                    >
                        <date-picker-input slot-scope="sc" v-bind="sc" />
                    </v-date-picker>
                </tabler-form-group>

                <tabler-form-group label="Datum van uitschrijving"
                                   horizontal
                                   label-cols="5"
                >
                    <v-date-picker mode="single"
                                   v-model="newValues.end"
                                   :min-date="newValues.start || newValues.application"
                    >
                        <date-picker-input slot-scope="sc" v-bind="sc" />
                    </v-date-picker>
                </tabler-form-group>

            </template>
            <template v-else>
                <tabler-timeline>
                    <tabler-timeline-item v-if="hasApplication"
                                          :time="applicationMoment | date('lg') "
                                          badge="yellow"
                                          :label="applicationLabel"
                    />

                    <tabler-timeline-item v-if="hasApplication"
                                          :label="noviceTimeLabel"
                                          :duration="noviceDuration"
                    />

                    <tabler-timeline-item v-if="hasStart"
                                          :time="startMoment | date('lg') "
                                          badge="green"
                                          :label="startLabel"
                    />

                    <tabler-timeline-item v-if="hasStart"
                                          :label="memberTimeLabel"
                                          :duration="membershipDuration"
                    />

                    <tabler-timeline-item v-if="hasEnd && !hasApplication && !hasStart">
                        <em class="text-muted">Start Lidmaatschap onbekend...</em>
                    </tabler-timeline-item>


                    <tabler-timeline-item v-if="hasEnd"
                                          :time="endMoment | date('lg') "
                                          badge="red"
                                          :label="endLabel"
                    />

                    <tabler-timeline-item v-if="hasEnd"
                                          :label="formerMemberTimeLabel"
                                          :duration="formerMembershipDuration"
                    />

                </tabler-timeline>
            </template>

            <div style="position:absolute;right:0px;bottom:0px;">
                <subtile-form-button icon="edit-3"
                                     color="blue"
                                     v-if="!editMode"
                                     @click="startEditDates"
                />

                <subtile-form-button icon="save"
                                     color="green"
                                     v-if="editMode"
                                     @click="submitEditDates"
                />

                <subtile-form-button icon="x"
                                     color="red"
                                     v-if="editMode"
                                     @click="cancelEditDates"
                />
            </div>

        </div>

        <template slot="footer">
            <div class="text-right">
                <b-button v-if="!hasEnd && !hasStart && hasApplication" @click="startMembership">
                    Lid Maken
                </b-button>

                <b-button v-if="!hasEnd && (hasStart || hasApplication)" @click="stopMembership">
                    Uitschrijven
                </b-button>

                <b-button variant="danger" v-if="hasEnd" @click="deleteMembership">
                    <base-icon icon="trash" from="fe" class="mr-1" />
                    Verwijderen
                </b-button>
            </div>
        </template>

    </tabler-card>
</template>

<script>
    import TablerCard from "../layouts/cards/TablerCard";
    import TablerTimeline from "../layouts/cards/TablerTimeline";
    import TablerTimelineItem from "../layouts/cards/TablerTimelineItem";
    import displayFilters from "../../utils/filters/display";
    import moment from "moment";
    import SubtileCardBodyForm from "../inputs/subtile/SubtileCardBodyForm";
    import BaseIcon from "./BaseIcon";
    import SubtileFormButton from "../inputs/subtile/SubtileFormButton";
    import TablerFormGroup from "../layouts/forms/TablerFormGroup";
    import DatePickerInput from "../inputs/DatePickerInput";
    import { getPersonMembershipsQuery } from "../../apis/graphql/queries/persons.graphql";
    import gql from "graphql-tag";

    const FRAGMENT = gql`
        fragment DisplayMembershipCard on Membership {
            id
            application
            start
            end
            remarks
        }
    `;

    export default {
        components: {
            DatePickerInput,
            TablerFormGroup,
            SubtileFormButton,
            BaseIcon,
            SubtileCardBodyForm,
            TablerTimelineItem,
            TablerTimeline,
            TablerCard
        },
        name: "display-membership-card",

        fragment:FRAGMENT,

        filters: { ...displayFilters },

        data() {
            return {
                collapsed:true,
                editMode:false,
                newValues:{
                    application:null,
                    start:null,
                    end:null,
                }
            }
        },

        props: {
            membership: {
                type:Object,
                default() {
                    return {
                        application:null,
                        start:null,
                        end:null,
                    }
                }
            },

            personId:[String,Number],

            applicationLabel:{
                type:String,
                default:"Inschrijving"
            },

            startLabel:{
                type:String,
                default:"Lid geworden"
            },

            endLabel: {
                type:String,
                default:"Uitgeschreven"
            },

            noviceTimeLabel: {
                type:String,
                default:"Kennismakingstijd",
            },

            memberTimeLabel: {
                type:String,
                default:"Lidmaatschap",
            },

            formerMemberTimeLabel: {
                type:String,
                default:"Oud-lid"
            }
        },

        computed: {

            hasApplication() {
                return !! this.membership.application;
            },

            applicationMoment() {
                if(this.membership.application) {
                    return moment(this.membership.application);
                }
                return null;
            },

            noviceDuration() {
                if(this.hasApplication) {
                    if(this.hasStart) {
                        return moment.duration(this.startMoment.diff(this.applicationMoment));
                    }

                    if(this.hasEnd) {
                        return moment.duration(this.endMoment.diff(this.applicationMoment));
                    }

                    return moment.duration(moment().diff(this.applicationMoment));
                }
                return null;
            },

            hasStart() {
                return !! this.membership.start;
            },

            startMoment() {
                if(this.membership.start) {
                    return moment(this.membership.start);
                }
                return null;
            },

            membershipDuration() {
                if(this.hasStart) {
                    if(this.hasEnd) {
                        return moment.duration(this.endMoment.diff(this.startMoment));
                    }

                    return moment.duration(moment().diff(this.startMoment));
                }
                return null;
            },

            hasEnd() {
                return !! this.membership.end;
            },

            endMoment() {
                if(this.membership.end) {
                    return moment(this.membership.end);
                }
            },

            formerMembershipDuration() {
                if(this.hasEnd) {
                    return moment.duration(moment().diff(this.endMoment));
                }
                return null;
            },

            status() {
                const now = moment();

                if(this.hasEnd && this.endMoment.isBefore(now)) {
                    return 'FORMER_MEMBER';
                }

                if(this.hasStart && this.startMoment.isBefore(now)) {
                    return 'MEMBER';
                }

                if(this.hasApplication && this.applicationMoment.isBefore(now)) {
                    return 'NOVICE';
                }

                return 'OUTSIDER';
            },

            remarks() {
                if(this.membership.remarks) {
                    return this.membership.remarks;
                }
                return "";
            }
        },

        methods: {

            startEditDates() {
                this.editMode = true;
                this.newValues = {
                    application: this.hasApplication ? this.applicationMoment.toDate() : null,
                    start: this.hasStart ? this.startMoment.toDate() : null,
                    end: this.hasEnd ? this.endMoment.toDate() : null,
                };
                this.$emit('start-edit-dates');
            },

            cancelEditDates() {
                this.editMode = false;
                this.$emit('cancel-edit-dates');
            },

            parseInputDate(input) {
                if(input) {
                    const m = moment(input);
                    if(m.isValid()) {
                        return m.format("YYYY-MM-DD");
                    }
                }
                return null;
            },

            submitEditDates() {
                this.editMode = false;
                this.$emit('submit-edit-dates');

                const id = this.membership.id;
                const person_id = this.personId;
                const application = this.parseInputDate(this.newValues.application);
                const start = this.parseInputDate(this.newValues.start);
                const end = this.parseInputDate(this.newValues.end);
                const status = this.status;

                this.$apollo.mutate({
                    mutation: gql`mutation updateMembershipDates($id:ID!, $application:Date, $start:Date, $end:Date) {
                                membership:updateMembership(id:$id, application:$application, start:$start, end:$end) {
                                    id,
                                    person_id,
                                    application,
                                    start,
                                    end,
                                    status,
                                }
                    }`,
                    variables: {id, application, start, end},

                    optimisticResponse: {
                        __typename:'Mutation',
                        membership: {
                            __typename:'Membership',
                            id, person_id, application, start, end, status
                        }
                    }
                }).then(data => console.log(data));

            },

            startMembership() {
                const id = this.membership.id;
                const application = this.membership.application;
                const start = moment().format("YYYY-MM-DD");
                const end = null;
                const status = 'MEMBER';

                this.$apollo.mutate({
                    mutation: gql`
                        mutation startMembership($id:ID!) {
                            membership:startMembership(id:$id) {
                                id,
                                application,
                                start,
                                end,
                                status
                            }
                        }
                    `,
                    variables:{id},
                    optimisticResponse: {
                        __typename:'Mutation',
                        membership: {
                            __typename:'Membership',
                            id, application, start, end, status
                        }
                    },
                });
            },

            stopMembership() {
                const id = this.membership.id;
                const application = this.membership.application;
                const start = this.membership.start;
                const end = moment().format("YYYY-MM-DD");
                const status = "FORMER_MEMBER";

                this.$apollo.mutate({
                    mutation: gql`
                        mutation stopMembership($id:ID!) {
                            membership:stopMembership(id:$id) {
                                id,
                                application,
                                start,
                                end,
                                status
                            }
                        }
                    `,
                    variables:{id},
                    optimisticResponse: {
                        __typename:'Mutation',
                        membership: {
                            __typename:'Membership',
                            id, application, start, end, status
                        }
                    },
                });
            },



            deleteMembership() {
                const id = this.membership.id;
                const person_id = this.personId;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation deleteMembership($id:ID!) {
                            deleteMembership(id:$id) {
                                id
                                person_id
                            }
                        }
                    `,
                    variables:{id},

                    update:(store, {data: {deleteMembership: {id, person_id}}}) => {
                        const fragment = gql`
                            fragment PersonMemberships on Person {
                                id
                                certificates {
                                    data {
                                        ...DisplayMembershipCard
                                    }
                                }
                            }
                            ${FRAGMENT}
                        `;

                        const person = store.readFragment({
                            id:person_id,
                            fragment:fragment,
                            fragmentName:'PersonMemberships'
                        });

                        console.log(person);

                        person.certificates.data = person.certificates.data.filter(certificate => certificate.id !== id);

                        store.writeFragment({
                            id:person_id,
                            fragment:fragment,
                            fragmentName:'PersonMemberships',
                            data:person
                        })
                    },

                    optimisticResponse: {
                        __typename:'Mutation',
                        deleteMembership: {
                            __typename:'Membership',
                            id, person_id
                        }
                    }

                });

            },

            handleSubmitRemarks(newValue) {
                const id = this.membership.id;
                const remarks = newValue;

                this.$apollo.mutate({
                    mutation: gql`
                        mutation updateMembershipRemarks($id: ID!, $remarks: String) {
                            updateMembership(id: $id, remarks: $remarks) {
                              id
                              remarks
                            }
                        }`,
                    variables: { id, remarks },

                    optimisticResponse: {
                        __typename:'Mutation',
                        updateMembership: {
                            __typename:'Membership',
                            id, remarks
                        }
                    }
                })
            },

        }
    }
</script>

<style scoped>

</style>