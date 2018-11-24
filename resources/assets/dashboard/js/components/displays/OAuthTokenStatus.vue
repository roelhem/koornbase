<template>
    <span>
        <base-status-icon ref="statusIcon" :color="status.color" v-b-tooltip.hover.bottom.html="tooltip" />
        <template v-if="!iconOnly">
            <base-field title="Status van OAuthToken" name="status">{{ status.label }}</base-field>

            <template v-if="status.showExpiresAt">
                <span class="text-muted">{{ status.expireText }}</span>
                <base-field class="text-muted-dark" title="Datum waarop token verloopt." name="expiresAt">
                    {{ token.expiresAt | date(dateSize) }}
                </base-field>
                <span class="text-muted">-</span>
                <base-field class="text-muted-dark" title="Tijd wanneer token verloopt" name="expiresAt">
                    {{ token.expiresAt | time(timeSize || dateSize) }}
                </base-field>
            </template>
        </template>
    </span>
</template>

<script>
    import gql from "graphql-tag";
    import moment from "moment";
    import displayFilters from '../../utils/filters/display';
    import BaseStatusIcon from "./BaseStatusIcon";
    import BaseField from "./BaseField";

    // Configuration of the standard statuses

    // REVOKED
    const REVOKED = {
        key:'REVOKED',
        label:'Ingetrokken',
        color:'red',
        showExpiresAt:false,
    };

    // EXPIRED
    const EXPIRED = {
        key:'EXPIRED',
        label:'Verlopen',
        color:'orange',
        showExpiresAt:true,
        expireText:'op'
    };

    // VALID
    const VALID = {
        key:'VALID',
        label:'Geldig',
        color:'green',
        showExpiresAt:true,
        expireText:'tot'
    };

    export default {
        components: {
            BaseField,
            BaseStatusIcon},
        name: "o-auth-token-status",

        fragment:gql`
            fragment OAuthTokenStatus on OAuthToken {
                revoked
                expiresAt
            }
        `,

        filters:displayFilters,

        props:{
            token:{
                type:Object,
                required:true
            },

            dateSize:{
                type:String,
                default:'sm'
            },

            timeSize:{
                type:String
            },

            iconOnly:{
                type:Boolean,
                default:false
            }
        },

        computed:{
            status() {
                if(this.token.revoked) {
                    return REVOKED;
                }

                if(this.token.expiresAt && moment().isAfter(this.token.expiresAt)) {
                    return EXPIRED;
                }

                return VALID;
            },
        },

        methods:{
            tooltip() {
                if(this.iconOnly) {
                    let res = `${this.status.label}`;
                    if(this.status.showExpiresAt && this.token.expiresAt) {
                        res += ` <em>${this.status.expireText} ${moment(this.token.expiresAt).format('DD-MM-YYYY HH:mm')}</em>`
                    }
                    return res;
                }
                return false;
            }
        }
    }
</script>

<style scoped>

</style>