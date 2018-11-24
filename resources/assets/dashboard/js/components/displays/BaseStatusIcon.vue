<template>

    <span class="status-icon" :class="backgroundClass" v-bind="$attrs" v-on="$listeners"></span>

</template>

<script>

    const COLOR_CLASS_PREFIX = 'bg-';

    export default {
        name: "base-status-icon",

        props: {
            color:String
        },

        computed: {
            backgroundClass() {
                if(typeof this.color === 'string') {

                    if(this.color.substr(0,COLOR_CLASS_PREFIX.length) === COLOR_CLASS_PREFIX) {
                        return this.color.substr(COLOR_CLASS_PREFIX.length);
                    }

                    return COLOR_CLASS_PREFIX+this.color;
                }

                return null;
            },
        }
    }
</script>

<style scoped>

</style>