<template>

    <span class="membership-status">
        <span class="membership-status-icon status-icon" :class="bgClass"></span>
        <span class="membership-status-label small">{{ label }}</span>
    </span>

</template>

<script>

    export default {
        name: "membership-status",
        props: {
            value:Number,
            labelOutsider:{
                type:String,
                default:'Buitenstaander',
            },
            labelFormerMember:{
                type:String,
                default:'Voormalig lid',
            },
            labelNovice:{
                type:String,
                default:'Kennismaker',
            },
            labelMember:{
                type:String,
                default:'Lid',
            },
        },
        computed: {
            label: function () {
                switch (this.value) {
                    case 0: return this.labelOutsider;
                    case 1: return this.labelNovice;
                    case 2: return this.labelMember;
                    case 3: return this.labelFormerMember;
                    default: return 'Onbekend';
                }
            },
            bgClass: function () {
                switch (this.value) {
                    case 0: return 'bg-secondary';
                    case 1: return 'bg-warning';
                    case 2: return 'bg-success';
                    case 3: return 'bg-danger';
                    default: return 'bg-light';
                }
            }
        }
    }
</script>

<style scoped>

</style>