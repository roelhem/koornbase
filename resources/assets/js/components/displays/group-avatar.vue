<template>

    <span class="group-tag-avatar tag-avatar avatar" :class="avatarColorClass">
        <i v-if="config !== null" class="fa"
           :class="[config.icon ? 'fa-'+config.icon : '', config.spin ? 'fa-spin' : '']"></i>
    </span>

</template>

<script>
    export default {
        name: "group-avatar",
        props: {
            'config': {
                default: function() {
                    return {
                        color:null,
                        icon:'users'
                    }
                }
            }
        },
        computed: {
            avatarColorClass: function() {
                let color = this.config.color;
                if(color) {
                    return 'avatar-'+color;
                } else {
                    return null;
                }
            },
            avatarIconClass: function() {
                let icon = this.config.icon;
                if(icon) {
                    return 'fa-'+icon;
                } else {
                    return null;
                }
            }
        }
    }
</script>

<style scoped>

    .tag-avatar {
        line-height:20px;
    }

    .tag-avatar fa {
        font-size:16px;
    }

</style>