<template>
    <span class="tag" :class="tagClass">

        <base-avatar v-if="showAvatar" tag :default-style="defaultStyle" v-bind="avatar" />

        <slot>
            {{ label }}
        </slot>

        <a v-if="removeButton"
           href="javascript:void(0);"
           class="tag-addon"
           @click.prevent="event => $emit('remove-click', event)">
            <i class="fe fe-x"></i>
        </a>
    </span>
</template>

<script>
    import BaseAvatar from "./BaseAvatar";
    import useDefaultStyle from '../mixins/useDefaultStyle';
    import { TAG_COLORS } from '../constants/style';


    export default {
        name: "base-tag",

        mixins:[useDefaultStyle],
        components:{BaseAvatar},

        props: {
            label:String,
            avatar:Object,

            rounded:{
                type:Boolean,
                default:false
            },

            color:{
                type:[Boolean,String],
                default:false,
                validator:function(val) {
                    return val === false || TAG_COLORS.indexOf(val) !== -1;
                }
            },

            removeButton:{
                type:Boolean,
                default:false,
            }
        },

        computed: {

            showAvatar: function() {
                return this.avatar || this.getStyle('tag.alwaysShowAvatar');
            },

            currentColor: function() {
                return this.color || this.getStyle('tag.color');
            },

            tagClass: function() {
                let res = [];

                if(this.rounded) {
                    res.push('tag-rounded');
                }

                if(this.currentColor) {
                    res.push('tag-'+this.currentColor);
                }

                return res;
            },
        },
    }
</script>

<style scoped>

</style>