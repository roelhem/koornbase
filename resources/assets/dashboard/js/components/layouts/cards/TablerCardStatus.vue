<template>
    <div class="card-status" :class="statusClasses"></div>
</template>

<script>
    import { BACKGROUND_COLORS } from "../../../constants/style";

    export default {
        name: "tabler-card-status",

        props: {

            color:{
                type:String,
                validate:function(val) {
                    return BACKGROUND_COLORS.indexOf(val) !== -1;
                }
            },

            left:{
                type:Boolean,
                default:false,
            }
        },

        computed: {

            colorClass:function() {
                if(this.color) {
                    return 'bg-'+this.color;
                } else {
                    return undefined;
                }
            },

            statusClasses:function() {
                let res = [];
                if(this.colorClass) {
                    res.push(this.colorClass);
                }
                if(this.left) {
                    res.push('card-status-left');
                }
                return res;
            }

        }
    }
</script>

<style scoped>

</style>