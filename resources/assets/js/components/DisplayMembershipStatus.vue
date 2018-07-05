<template>

    <span>
        <span class="status-icon" :class="statusBackgroundClass"></span>
        <data-display title="Lid-status">{{ label }}</data-display>
        <span v-if="since" class="small text-muted">
            (sinds
            <data-display title="Lid-status"
                          class="text-muted-dark"
            >{{ since | moment('dd D MMMM YYYY') }}</data-display>
            )
        </span>
    </span>

</template>

<script>
    import DataDisplay from "./displays/data-display";

    export default {
        components: {DataDisplay},
        name: "display-membership-status",
        props: {
            status:[String,Number],
            title:String,
            since:String
        },

        computed: {
            intValue: function () {
                let value = this.status;
                if(typeof value === 'string') {
                    value = parseInt(status);
                }
                return value;
            },

            label: function() {
                if(this.title) {
                    return this.title;
                }
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