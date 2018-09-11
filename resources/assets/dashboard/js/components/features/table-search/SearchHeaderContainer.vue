<template>


    <b-container>

        <b-row>
            <!-- START: Small Pager -->
            <b-col md="auto">
                <search-simple-pager v-model="currentPage"
                                     :total="total"
                                     :per-page="perPage" />
            </b-col>
            <!-- END: Small Pager -->


            <!-- START: Search Status -->
            <b-col align-self="center">
                <search-status-display :records-name="recordsName"
                                       :is-loading="isLoading"
                                       :from="from"
                                       :to="to"
                                       :total="total" />
            </b-col>
            <!-- END: Search Status -->


            <!-- START: Other Actions -->
            <b-col md="auto">
                <slot />
            </b-col>
            <!-- END: Other Actions -->
        </b-row>

        <hr class="my-3" />
    </b-container>


</template>

<script>
    import SearchStatusDisplay from "./SearchStatusDisplay";
    import SearchSimplePager from "./SearchSimplePager";

    export default {
        name: "search-header-container",

        model:{
            prop:'page',
            event:'change'
        },

        props:{
            page:{
                type:Number
            },
            from:{
                type:Number
            },
            to:{
                type:Number,
            },
            total:{
                type:Number,
                default:0
            },
            perPage:{
                type:Number,
                default:0
            },
            isLoading:{
                type:Boolean,
                default:false
            },
            recordsName:{
                type:String
            }
        },

        computed: {

            currentPage: {
                get() {
                    return this.page;
                },
                set(newValue) {
                    this.$emit('change', newValue);
                }
            },

        },

        components:{
            SearchStatusDisplay,
            SearchSimplePager
        }
    }
</script>

<style scoped>

</style>