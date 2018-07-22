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
            status:String,
            title:String,
            since:String
        },

        computed: {
            label: function() {
                if(this.title) {
                    return this.title;
                }
                switch (this.status) {
                    case "NOVICE": return 'Kennismaker';
                    case "MEMBER": return 'Lid';
                    case "FORMER_MEMBER": return 'Voormalig Lid';
                    case "OUTSIDER": return 'Buitenstaander';
                    default: return 'Onbekend';
                }
            },

            statusBackgroundClass: function() {
                switch(this.status) {
                    case "NOVICE": return 'bg-yellow';
                    case "MEMBER": return 'bg-green';
                    case "FORMER_MEMBER": return 'bg-red';
                    case "OUTSIDER": return 'bg-gray';
                    default: return 'bg-gray-dark';
                }
            }
        }
    }
</script>

<style scoped>

</style>