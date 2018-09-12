<template>
    <tabler-card
            :icon-from="iconFrom"
            v-bind="$attrs"
            v-on="$listeners"
            no-body
    >

        <template slot="title">


            <slot name="title">{{ title }}</slot>

            <span v-if="showCount" class="text-muted">
                ({{ total }})
            </span>

        </template>




        <slot />
    </tabler-card>
</template>

<script>
    import TablerCard from "../layouts/cards/TablerCard";

    export default {
        name: "person-contact-entries-card",

        components: {
            TablerCard
        },

        props: {

            entries:Object,

            hideCount:{
                type:Boolean,
                default:false,
            },

            title: {
                type:String,
                default:"Contact informatie"
            },

            iconFrom: {
                default() {
                    return ["fe", "fa"];
                },
            }
        },

        computed: {

            total() {
                if(this.entries && typeof this.entries.total === 'number') {
                    return this.entries.total;
                }
                if (this.entries && this.entries.data) {
                    return this.entries.data.length;
                }
                return 0;
            },

            showCount() {
                return !this.hideCount;
            }
        }
    }
</script>

<style scoped>

</style>