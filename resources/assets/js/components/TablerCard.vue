<template>

    <div class="card" :class="cardClass">

        <tabler-card-status v-if="status" :color="status" :left="statusLeft" />

        <div class="card-header" @click="headerClickHandler">
            <slot name="header">
                <h3 class="card-title">
                    <base-icon :icon="icon" :from="iconFrom" class="text-muted-dark mr-1" />
                    <slot name="title">{{ title }}</slot>
                </h3>

                <div v-if="showCardOptions" class="card-options">

                    <slot name="options"></slot>

                    <a v-if="collapsible"
                       href="#"
                       class="card-options-collapse"
                       @click.prevent="toggleCollapse()">
                        <base-icon icon="chevron-up" from="fe" />
                    </a>

                    <a v-if="maximizable"
                       href="#"
                       class="card-options-fullscreen"
                       @click="$emit('update:fullscreen', !fullscreen)">
                        <base-icon icon="maximize" from="fe" />
                    </a>

                    <a v-if="removable"
                       href="#"
                       class="card-options-remove"
                       @click.prevent="$emit('remove', true)">
                        <base-icon icon="x" from="fe" />
                    </a>
                </div>
            </slot>
        </div>

        <tabler-dimmer :active="isLoading">
            <div v-if="!noBody" class="card-body">
                <slot></slot>
            </div>
            <template v-else>
                <slot></slot>
            </template>

            <div v-if="$slots.footer" class="card-footer">
                <slot name="footer"></slot>
            </div>
        </tabler-dimmer>
    </div>

</template>

<script>
    import TablerCardStatus from "./TablerCardStatus";
    import TablerDimmer from "./TablerDimmer";
    import BaseIcon from "./BaseIcon";

    export default {
        components: {
            BaseIcon,
            TablerCardStatus, TablerDimmer},
        name: "tabler-card",

        props: {

            title:String,
            icon:[String,Object],
            iconFrom:{
                type:[String, Array],
                default:function() {
                    return ['fe','fa'];
                }
            },
            status:String,
            statusLeft:{
                type:Boolean,
                default:false,
            },

            noBody:{
                type:Boolean,
                default:false
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

            showCardOptions:function() {
                if(this.collapsible || this.removable || this.maximizable) {
                    return true;
                } else if(this.$slots.options) {
                    return true;
                } else {
                    return false;
                }
            },

            cardClass:function() {
                let res = [];
                if(this.collapsed) {
                    res.push('card-collapsed');
                }
                if(this.fullscreen) {
                    res.push('card-fullscreen');
                }
                return res;
            }

        },

        methods: {

            toggleCollapse() {
                this.$emit('update:collapsed', !this.collapsed);
            },

            headerClickHandler() {
                if(this.collapsibleWithHeader) {
                    this.toggleCollapse();
                }
            }

        },

    }
</script>

<style scoped>



</style>