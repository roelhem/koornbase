<template>
    <b-button-group>

        <b-button :disabled="!allowBack"
                  ref="firstButton"
                  :variant="variant"
                  :class="buttonClass"
                  @click="toFirstPage">
            <base-icon :icon="icons.first" :from="iconsFrom" />
        </b-button>

        <b-button :disabled="!allowBack"
                  ref="prevButton"
                  :variant="variant"
                  :class="buttonClass"
                  @click="toPrevPage">
            <base-icon :icon="icons.prev" :from="iconsFrom" />
        </b-button>

        <b-button :disabled="!allowForward"
                  ref="nextButton"
                  :variant="variant"
                  :class="buttonClass"
                  @click="toNextPage">
            <base-icon :icon="icons.next" :from="iconsFrom" />
        </b-button>

        <b-button :disabled="!allowForward"
                  ref="lastButton"
                  :variant="variant"
                  :class="buttonClass"
                  @click="toLastPage">
            <base-icon :icon="icons.last" :from="iconsFrom" />
        </b-button>

    </b-button-group>
</template>

<script>
    import BaseIcon from "../../displays/BaseIcon";

    export default {
        components: {BaseIcon},
        name: "search-simple-pager",

        model: {
            prop: 'page',
            event: 'change',
        },

        props: {

            page: {
                type:Number,
                default:1,
            },

            total: {
                type:Number,
                default:0,
            },

            perPage: {
                type:Number,
                default:1
            },

            variant:{
                type:String,
                default:'secondary',
            },

            buttonClass:{
                type:[String,Array],
                default: 'text-muted-dark',
            },

            iconsFrom:{
                type:[String,Array],
                default: function() {
                    return ['fe','fa'];
                }
            },

            icons:{
                default: function() {
                    return {
                        first: { fe:'rewind', fa:'fast-backward' },
                        prev:  { fe:'skip-back', fa:'step-backward' },
                        next:  { fe:'skip-forward', fa:'step-forward' },
                        last:  { fe:'fast-forward', fa:'fast-forward' }
                    };
                }
            },

        },

        computed: {

            allowBack:function() {
                return this.page > 1;
            },

            allowForward:function() {
                return this.page < this.lastPage;
            },

            lastPage:function() {
                return Math.ceil(this.total / this.perPage);
            }
        },

        methods: {

            changePage(page) {
                this.$emit('change', page);
            },

            toFirstPage() {
                this.changePage(1);
            },

            toLastPage() {
                this.changePage(this.lastPage);
            },

            toPrevPage() {
                if(this.page > 1) {
                    this.changePage(this.page - 1);
                } else {
                    this.toFirstPage();
                }
            },

            toNextPage() {
                if(this.page < this.lastPage) {
                    this.changePage(this.page + 1);
                } else {
                    this.toLastPage();
                }
            },



        }
    }
</script>

<style scoped>

</style>