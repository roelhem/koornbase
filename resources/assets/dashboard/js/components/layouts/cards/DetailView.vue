<template>
    <div>
        <table class="table" :class="tableClasses">
            <tbody>
                <slot></slot>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "detail-view",

        props: {

            sm:{
                type:Boolean,
                default:false,
            },

            inCard:{
                type:Boolean,
                default:false,
            }

        },

        computed: {

            tableClasses:function() {
                let res = [];
                if(this.sm) {
                    res.push('table-sm');
                }
                if(this.inCard) {
                    res.push('card-table');
                }
                return res;
            }

        }
    }
</script>

<style scoped>

</style>