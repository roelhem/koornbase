<template>

    <span>
        <span class="status-icon" :class="statusBackgroundClass"></span>
        <data-display class="small" title="Lidstatus">{{ label }}</data-display>
        <span class="small font-italic text-muted" v-if="since">
            ( Sinds
            <data-display class="text-muted-dark" title="Lidstatus sinds">{{ since | moment('dd D MMMM YYYY') }}</data-display>
            )
        </span>
    </span>

</template>

<script>
    export default {
        name: "display-person-membership-status",
        props: {
            value:{
                type:[String,Number],
                default:0,
            },

            since: {
                type:[String, Date]
            }

        },

        computed: {
            intValue: function () {
                let value = this.value;
                if(typeof value === 'string') {
                    value = parseInt(value);
                }
                return value;
            },

            label: function() {
                switch (this.intValue) {
                    case 1: return 'Kennismaker';
                    case 2: return 'Lid';
                    case 3: return 'Voormalig Lid';
                    case 0: return 'Buitenstaander';
                    default: return 'Onbekend';
                }
            },

            statusBackgroundClass: function() {
                switch(this.intValue) {
                    case 1: return 'bg-yellow';
                    case 2: return 'bg-green';
                    case 3: return 'bg-red';
                    case 0: return 'bg-gray';
                    default: return 'bg-gray-dark';
                }
            }
        }
    }
</script>

<style scoped>

</style>