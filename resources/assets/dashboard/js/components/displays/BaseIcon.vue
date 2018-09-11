<template>
    <i :class="iconClass"></i>
</template>

<script>
    import { ICON_SETS } from "../../constants/style";

    export default {
        name: "base-icon",

        props: {

            icon:{
                type:[String, Object],
                required:true,
                default:function() {
                    return {};
                }
            },

            from:{
                type:[String,Array],
                default:function() {
                    return ICON_SETS;
                }
            }
        },


        computed: {

            iconSet: function() {
                if(typeof this.from === 'string') {
                    return this.from;
                }

                if(typeof this.icon === 'string') {
                    return this.from.length > 0 ? this.from[0] : null;
                }

                for(let i = 0; i < this.from.length; i++) {
                    let set = this.from[i];
                    if(set in this.icon) {
                        return set;
                    }
                }

                return this.from.length > 0 ? this.from[0] : null;
            },

            iconName: function() {
                if(typeof this.icon === 'string') {
                    return this.icon;
                } else if(this.iconSet in this.icon) {
                    return this.icon[this.iconSet];
                } else {
                    return null;
                }
            },

            iconClass: function() {
                switch (this.iconSet) {
                    default:
                        return [this.iconSet, this.iconSet+'-'+this.iconName];
                }
            }

        }
    }
</script>

<style scoped>

</style>