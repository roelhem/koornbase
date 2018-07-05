<template>
    <tr>
        <th :title="title">
            <slot name="label">
                <base-icon v-if="useIcon"
                           :icon="icon"
                           :from="iconFrom"
                           class="text-muted" />
                {{ label }}
            </slot>
        </th>
        <td><slot></slot></td>
    </tr>
</template>

<script>
    import BaseIcon from "./BaseIcon";

    export default {
        components: {BaseIcon},
        name: "detail-entry",

        props: {

            label:String,

            icon:{
                type:[String,Object],
                default:null,
            },

            iconFrom:{
                type:[String,Array],
                default:function() {
                    return ['fa','fe']
                }
            },

            title:null

        },

        computed: {
            useIcon:function() {
                return !!this.icon;
            }
        }
    }
</script>

<style scoped>

</style>