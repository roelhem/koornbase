<template>
    <div>
        <div v-if="currentState === 'loading'" key="message-loading">
            Bezig met laden...
        </div>
        <div v-else-if="currentState === 'displaying'" key="message-displaying">
            Toont
            <span style="font-weight: 700" class="mx-1">{{ meta.from }} - {{ meta.to }}</span>
            van de in totaal
            <span style="font-weight: 700" class="mx-1">{{ meta.total }}</span>
            gevonden {{ recordsName }}.

        </div>
    </div>
</template>

<script>
    export default {
        name: "search-status-display",

        props: {

            isLoading:{
                type:Boolean,
                default:false,
            },

            hasError: {
                type:Boolean,
                default:false,
            },

            meta: {
                type:Object
            },

            recordsName: {
                type:String,
                default:'records',
            }

        },

        computed: {
            currentState:function() {
                if(this.isLoading) {
                    return 'loading';
                } else if(this.meta) {
                    return 'displaying';
                }
            }
        }
    }
</script>

<style scoped>

</style>