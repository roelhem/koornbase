<template>
    <span class="tag" :class="tagClass">

        <base-avatar v-if="avatar" tag :default-color="defaultAvatarColor" v-bind="avatar" />

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
    import { TAG_COLORS } from '../constants/style';


    export default {
        name: "base-tag",
        components:{BaseAvatar},

        props: {
            label:String,
            avatar:Object,
            defaultAvatarColor:[Boolean,String],

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
            tagClass: function() {
                let res = [];

                if(this.rounded) {
                    res.push('tag-rounded');
                }

                if(this.color) {
                    res.push('tag-'+this.color);
                }

                return res;
            },
        },
    }
</script>

<style scoped>

</style>