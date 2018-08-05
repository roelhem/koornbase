<template>

    <b-breadcrumb-item v-bind="bBreadcrumbItemProps">
        <base-icon v-if="!!icon" :icon="icon" :from="iconFrom" class="mr-1" />
        <slot>
            {{ text }}
        </slot>
    </b-breadcrumb-item>

</template>

<script>
    import BaseIcon from "./BaseIcon";

    export default {
        components: {BaseIcon},
        name: "tabler-breadcrumb-item",

        props: {
            href:String,
            rel:String,
            target:String,
            active:Boolean,
            activeClass:String,
            append:Boolean,
            disabled:Boolean,
            event:[String,Array],
            exact:Boolean,
            exactActiveClass:String,
            replace:Boolean,
            routerTag:String,
            to:[String,Object],
            text:String,
            ariaCurrent:String,

            icon:[String, Object],
            iconFrom:{
                type:[String,Array],
                default:function() {
                    return ['fa','fe'];
                }
            }
        },

        computed: {
            bBreadcrumbItemProps: function() {
                return {
                    href:this.href,
                    rel:this.rel,
                    target:this.target,
                    active:this.active,
                    activeClass:this.activeClass,
                    append:this.append,
                    disabled:this.disabled,
                    event:this.event,
                    exact:this.exact,
                    exactActiveClass:this.exactActiveClass,
                    replace:this.replace,
                    routerTag:this.routerTag,
                    to:this.to,
                    text:this.text,
                    ariaCurrent:this.ariaCurrent,
                }
            },
        }
    }
</script>

<style scoped>

</style>