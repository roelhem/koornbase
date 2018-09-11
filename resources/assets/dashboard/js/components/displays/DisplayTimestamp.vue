<template>

    <div style="display: inline-block">
        <div class="small">
            {{ timestamp | date(dateSize) }}
            <span class="text-muted">&nbsp;om&nbsp;</span>
            <span class="text-muted-dark">{{ timestamp | time(timeSize) }}</span>
        </div>
        <div style="font-size: 0.67em;">
            <span :class="{'text-muted':inPast, 'text-orange':!inPast}">
                <template v-if="!inPast">Over </template>
                {{ duration.humanize() }}
                <template v-if="inPast"> geleden</template>.
            </span>
        </div>
    </div>

</template>

<script>
    import moment from "moment";
    import displayFilters from '../../utils/filters/display';

    export default {
        name: "display-timestamp",

        filters:displayFilters,

        props: {
            timestamp:[String],
            dateSize: {
                type:String,
                default:'sm'
            },
            timeSize: {
                type:String,
                default:'sm'
            },
        },

        computed: {

            moment() {
                return moment(this.timestamp);
            },

            diff() {
                return this.moment.diff(moment());
            },

            duration() {
                return moment.duration(this.diff);
            },

            inPast() {
                return this.diff <= 0;
            }

        }
    }
</script>

<style scoped>

</style>