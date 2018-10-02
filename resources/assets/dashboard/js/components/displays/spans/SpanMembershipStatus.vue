<template>

    <span>
        <span class="status-icon" :class="membershipStatus.type | membershipStatusColor"></span>
        <base-field v-if="title" title="Lid-status titel">{{ title }}</base-field>
        <base-field v-else title="Lid-status">{{ membershipStatus.type | membershipStatusName }}</base-field>
        <span v-if="membershipStatus.since" class="small text-muted">
            (sinds
            <base-field title="Lid-status"
                          class="text-muted-dark"
            >{{ membershipStatus.since | date(dateSize) }}</base-field>
            )
        </span>
    </span>

</template>

<script>
    import gql from "graphql-tag";
    import displayFilters from '../../../utils/filters/display';
    import BaseField from "../BaseField";

    export default {
        components: {
            BaseField
        },

        fragment:gql`
            fragment SpanMembershipStatus on MembershipStatus {
                type
                since
            }
        `,

        name: "display-membership-status",

        props: {
            membershipStatus:{
                type:Object,
                required:true,
                default() {
                    return {
                        type:null,
                        since:null,
                    };
                }
            },

            title:String,

            dateSize:{
                type:String,
                default:'lg',
            }
        },

        filters: displayFilters,
    }
</script>

<style scoped>

</style>