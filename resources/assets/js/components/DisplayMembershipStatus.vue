<template>

    <span>
        <span class="status-icon" :class="statusBackgroundClass"></span>
        {{ label }}
    </span>

</template>

<script>
    export default {
        name: "display-membership-status",
        props: {
            value:{
                type:[String,Number],
                default:0,
            },
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