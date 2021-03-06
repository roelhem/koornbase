<template>

    <span class="avatar"
          :class="spanClass"
          :style="spanStyle">
        <template v-if="type === 'slot'"><slot /></template>
        <base-icon v-else-if="type === 'icon'" key="avatar-from-icon" :icon="this.icon || this.defaultIcon" />
        <span v-else-if="type === 'letters'" key="avatar-from-letters" class="avatar-letters">{{ letters }}</span>
        <template v-else>&nbsp;</template>

        <span v-if="status" class="avatar-status" :class="statusClass"></span>
    </span>

</template>

<script>

    import gql from "graphql-tag";
    import BaseIcon from './BaseIcon';
    import useDefaultStyle from '../../mixins/useDefaultStyle';
    import { AVATAR_COLORS, BACKGROUND_COLORS, AVATAR_SIZES } from '../../constants/style';

    export default {
        name: "base-avatar",

        components: {BaseIcon},
        mixins: [useDefaultStyle],

        fragment:gql`
            fragment BaseAvatar on Avatar {
                image
                letters
            }
        `,

        props: {
            image:String,
            letters:String,
            icon:[String, Object],

            placeholder: {
                type:Boolean,
                default:false,
            },

            color: {
                type:String,
                validator:function(val) {
                    return AVATAR_COLORS.indexOf(val) !== -1;
                }
            },

            colorInvert: {
                type:Boolean,
                default:false,
            },

            status: {
                type:[Boolean, String],
                default:false,
                validator: function(val) {
                    return val === true || val === false || BACKGROUND_COLORS.indexOf(val) !== -1;
                }
            },

            size: {
                type:[Boolean, String],
                default:false,
                validator:function(val) {
                    return val === true || val === false || AVATAR_SIZES.indexOf(val) !== -1;
                }
            },

            tag: {
                type:Boolean,
                default:false,
            }
        },

        computed: {

            type:function() {
                if(this.$slots.default) {
                    return 'slot';
                } else if(this.image) {
                    return 'image';
                } else if(this.letters) {
                    return 'letters';
                } else if(this.icon || this.defaultIcon) {
                    return 'icon';
                } else if(this.placeholder) {
                    return 'placeholder';
                } else {
                    return 'empty';
                }
            },

            defaultColor:function() {
                return this.getStyle('avatar.color', 'color');
            },

            defaultIcon:function() {
                return this.getStyle('avatar.icon','icon');
            },

            colorClass:function() {
                const color = this.color || this.defaultColor;
                if(color) {
                    return 'avatar-'+color;
                } else {
                    return undefined;
                }
            },

            spanClass:function() {
                let res = [this.colorClass];

                if(this.colorInvert) {
                    res.push('color-invert');
                }

                if(this.tag) {
                    res.push('tag-avatar');
                } else if(this.size) {
                    if(typeof this.size === 'string') {
                        res.push('avatar-'+this.size);
                    }
                }

                if(this.type === 'placeholder') {
                    res.push('avatar-placeholder');
                }

                return res;
            },

            spanStyle:function() {
                if(this.image) {
                    return {
                        'background-image':`url(${this.image})`
                    };
                }
            },

            statusClass:function() {
                return this.status === true ? null : 'bg-'+this.status;
            }

        }
    }
</script>

<style scoped>

</style>