<template>
    <span v-b-tooltip.hover :title="title" class="fa-stack">
        <base-icon :class="revoked ? 'text-yellow' : 'text-cyan'"
                   class="fa-stack-2x"
                   icon="circle"
                   from="fa"
        />
        <base-icon v-if="revoked"
                   class="fa-stack-1x fa-inverse fa-rotate-270"
                   icon="hand-stop-o"
                   from="fa"
        />
        <base-icon v-else
                   class="fa-stack-1x fa-inverse"
                   icon="handshake-o"
                   from="fa"
        />
    </span>
</template>

<script>
    import BaseIcon from "./BaseIcon";

    export default {
        name: "revoked-status-icon",


        components: {
            BaseIcon
        },

        props: {
            revoked:{
                type:Boolean,
                default:false
            },

            validText:{
                type:String,
                default:'In contact met server.',
            },

            revokedText:{
                type:String,
                default:'Contact met server ingetrokken.',
            }
        },

        computed: {
            title() {
                return this.revoked ? this.revokedText : this.validText;
            }
        }
    }
</script>

<style scoped>

</style>