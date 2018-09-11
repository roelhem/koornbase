<template>

    <tabler-card
        :icon="icon"
        :icon-from="iconFrom"
        :status="status"
        :status-left="statusLeft"
        no-body
        :collapsed="collapsed"
        v-on:update:collapsed="e => $emit('update:collapsed', e)"
        :collapsible="collapsible"
        :collapsibleWithHeader="collapsibleWithHeader"
        :removable="removable"
        @remove="e => $emit('remove', e)"
        :fullscreen="fullscreen"
        v-on:update:fullscreen="e => $emit('update:fullscreen', e)"
        :maximizable="maximizable"
        :is-loading="isLoading"
    >

        <template slot="title">
            <slot name="title">{{title}}</slot>

            <span v-if="!hideCount && !isLoading" class="text-muted">({{ rowCount }})</span>

            <span v-if="collapsed && hasRows" class="ml-3 text-muted small">
                <slot name="preview" :item="rows[0]" />
            </span>

        </template>

        <table class="table card-table">
            <tbody v-if="isLoading && rowCount === 0">
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
            </tbody>
            <tbody v-else>
                <tr v-for="(row, key) in rows" :key="key">
                    <slot name="row" :item="row">
                        <td>{{ row }}</td>
                    </slot>
                </tr>
            </tbody>
        </table>

    </tabler-card>

</template>

<script>
    import TablerCard from "./TablerCard";
    import TablerDimmer from "./TablerDimmer";

    export default {

        props: {

            rows:Array,

            title:String,
            icon:[String,Object],
            iconFrom:{
                type:[String, Array],
                default:function() {
                    return ['fe','fa'];
                }
            },

            hideCount:{
                type:Boolean,
                default:false
            },

            status:String,
            statusLeft:{
                type:Boolean,
                default:false,
            },

            isLoading:{
                type:Boolean,
                default:false
            },

            collapsed:{
                type:Boolean,
                default:false
            },

            collapsible:{
                type:Boolean,
                default:false
            },

            collapsibleWithHeader:{
                type:Boolean,
                default:false
            },

            removable:{
                type:Boolean,
                default:false
            },

            fullscreen:{
                type:Boolean,
                default:false
            },

            maximizable:{
                type:Boolean,
                default:false,
            }

        },

        computed: {
            hasRows: function() {
                return !!(this.rows && this.rows.length > 0);
            },

            rowCount: function() {
                if(this.rows) {
                    return this.rows.length;
                }
                return 0;
            }
        },

        components: {
            TablerDimmer,
            TablerCard},
        name: "tabler-table-card"
    }
</script>

<style scoped>

</style>