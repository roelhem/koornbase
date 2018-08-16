<template>
    <span :class="stampClass"
          @mouseover="e => $emit('mouseover', e)"
          @mouseleave="e => $emit('mouseleave', e)"
    >
        <base-icon v-if="currentIcon" :icon="currentIcon" :from="iconFrom" />
    </span>
</template>

<script>
    import BaseIcon from "./BaseIcon";
    import useDefaultStyle from '../mixins/useDefaultStyle';

    export default {
        components: {BaseIcon},
        name: "base-stamp",

        mixins:[useDefaultStyle],

        props: {

            size: String,
            md: {
                type:Boolean,
                default:false
            },
            inverted: {
                type:Boolean,
                default:false,
            },

            mixin:[useDefaultStyle],

            icon:[String,Object],
            iconFrom:{
                type:[String,Array],
                default:function() {
                    return ['fe','fa'];
                }
            },


            color:String,
        },

        computed: {


            currentColor: function() {
                return this.color || this.getStyle('stamp.color') || this.getStyle('color');
            },

            currentIcon: function() {
                return this.icon || this.getStyle('icon');
            },

            bgColor: function() {
                if(this.inverted) {
                    return 'bg-light';
                } else if(this.currentColor) {
                    return 'bg-' + this.currentColor;
                }
                return null;
            },

            fgColor: function() {
                if(this.inverted) {
                    return 'text-dark';
                }
                return null;
            },

            stampClass: function () {
                let res = ['stamp'];


                switch(this.size) {
                    case 'xs':
                        res.push('stamp-xs');
                        break;
                    case 'md':
                        res.push('stamp-md');
                        break;
                    case 'lg':
                        res.push('stamp-lg');
                        break;
                    case 'xs':
                        res.push('stamp-xl');
                        break;
                }

                if(this.bgColor) {
                    res.push(this.bgColor);
                }
                if(this.fgColor) {
                    res.push(this.fgColor);
                }

                return res;
            }
        }


    }
</script>

<style scoped>

</style>