<template>
    <tabler-card
            :icon-from="iconFrom"
            :collapsed="collapsed"
            v-bind="$attrs"
            v-on="$listeners"
            no-body
    >

        <template slot="title">


            <slot name="title">{{ title }}</slot>

            <span v-if="showCount" class="text-muted">
                ({{ total }})
            </span>

            <span v-if="showPreview" class="ml-3 text-muted small">
                <slot name="preview" :item="previewItem"></slot>
            </span>

        </template>



        <template v-if="!isEmpty">
            <slot />
        </template>

        <template v-if="showPlaceholder">
            <slot name="placeholder">
                <div class="card-body text-center text-muted font-italic">
                    {{ placeholderText }}
                </div>
            </slot>
        </template>

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

            previewEntry:Object,

            hideCount:{
                type:Boolean,
                default:false,
            },

            noPreview:{
                type:Boolean,
                default:false
            },

            noPlaceholder:{
                type:Boolean,
                default:false
            },

            placeholderText:{
                type:String,
                default:"Geen contactgegevens bekend..."
            },

            title: {
                type:String,
                default:"Contact informatie"
            },

            collapsed: {
                type:Boolean,
                default:false,
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

            // COUNTER


            showCount() {
                return !this.hideCount;
            },

            // PREVIEW

            showPreview() {
                return !this.noPreview && this.collapsed && this.previewItem;
            },

            previewItem() {
                if(this.previewEntry) {
                    return this.previewEntry;
                } else if(this.entries && this.entries.data && this.entries.data.length > 0) {
                    return this.entries.data[0];
                } else {
                    return null;
                }
            },

            // EMPTY

            isEmpty() {
                return this.total === 0;
            },

            showPlaceholder() {
                return !this.noPlaceholder && this.isEmpty;
            }
        }
    }
</script>

<style scoped>

</style>