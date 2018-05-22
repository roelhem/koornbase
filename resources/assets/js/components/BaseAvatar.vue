<template>

    <span class="avatar"
          :class="spanClass"
          :style="spanStyle">
        <template v-if="type === 'slot'"><slot /></template>
        <i v-else-if="type === 'icon'" :class="icon"></i>
        <span v-else-if="type === 'letters'" class="avatar-letters">{{ letters }}</span>
        <template v-else>&nbsp;</template>

        <span v-if="status" class="avatar-status" :class="statusClass"></span>
    </span>

</template>

<script>
    import { AVATAR_COLORS, BACKGROUND_COLORS, AVATAR_SIZES } from '../constants/style';

    export default {
        name: "base-avatar",

        props: {
            image:String,
            letters:String,
            icon:[String, Array],

            placeholder: {
                type:Boolean,
                default:false,
            },

            color: {
                type:[Boolean, String],
                default:false,
                validator:function(val) {
                    return val === false || AVATAR_COLORS.indexOf(val) !== -1;
                }
            },

            defaultColor: {
                type:[Boolean, String],
                default:false,
                validator:function(val) {
                    return val === false || AVATAR_COLORS.indexOf(val) !== -1;
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
                } else if(this.icon) {
                    return 'icon';
                } else if(this.letters) {
                    return 'letters';
                } else if(this.placeholder) {
                    return 'placeholder';
                } else {
                    return 'empty';
                }
            },

            spanClass:function() {
                let res = Array.isArray(this.classes) ? this.classes : (this.classes ? [this.classes] : []);

                if(this.color) {
                    if(typeof this.color === 'string') {
                        res.push('avatar-' + this.color);
                    }
                } else if(this.defaultColor) {
                    if(typeof this.defaultColor === 'string') {
                        res.push('avatar-' + this.defaultColor);
                    }
                }

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