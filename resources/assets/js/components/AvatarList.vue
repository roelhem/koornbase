<template>

    <div class="avatar-list" :class="{'avatar-list-stacked':stacked}">
        <template v-for="item in shownItems">
        <slot name="avatar" :item="item">
            <base-avatar v-bind="item.avatar"
                         :default-style="defaultStyle"
                         :size="size"
            />
        </slot>
        </template>
        <template v-if="isTruncated">
            <slot name="truncation">
                <base-avatar :size="size"
                             v-b-tooltip.hover.html="`En nog ${truncatedCount}... <em>(totaal ${totalCount})</em>`"
                >{{ truncatedCount }}<template v-if="truncatedCount < 100">+</template></base-avatar>
            </slot>
        </template>
    </div>

</template>

<script>
    import BaseAvatar from "./BaseAvatar";

    export default {
        components: {BaseAvatar},
        name: "avatar-list",

        props: {

            items: {
                type:Array,
                default:function() {
                    return [];
                }
            },

            max: {
                type:Number,
                default:8,
            },

            stacked: {
                type:Boolean,
                default:false,
            },

            defaultStyle:[String,Array],
            size:String,

        },

        computed: {

            isTruncated:function() {
                return this.items.length > this.max;
            },

            totalCount:function() {
                return this.items.length;
            },

            truncatedCount:function() {
                if(!this.isTruncated) {
                    return 0;
                }
                return this.items.length - (this.max - 1);
            },

            shownItems:function() {
                if(this.isTruncated) {
                    return this.items.slice(0, this.max-1);
                }
                return this.items;
            }

        }

    }
</script>

<style scoped>

</style>