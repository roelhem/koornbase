<template>
    <li class="timeline-item">
        <div v-if="hasBadge" class="timeline-badge" :class="badgeBgClass"></div>

        <slot>
            <span v-if="isDurationItem" class="text-muted font-italic">{{ label }}</span>
            <span v-else>{{ label }}</span>
        </slot>

        <div v-if="hasTime" class="timeline-time">
            <slot name="time">
                <em v-if="isDurationItem">( {{ durationText }} )</em>
                <template v-else>{{ time }}</template>
            </slot>
        </div>

    </li>
</template>

<script>
    export default {
        name: "tabler-timeline-item",

        props: {
            badge:String,
            time:String,
            duration:{
                default:false
            },
            label:String,
        },

        computed:{

            hasBadge() {
                return !!this.badge;
            },

            badgeBgClass() {
                return 'bg-'+this.badge;
            },

            hasTime() {
                return !!this.time || this.isDurationItem || !!this.$slots.time;
            },

            isDurationItem() {
                return this.duration !== false;
            },

            durationText() {
                if(this.duration && this.duration.humanize && typeof this.duration.humanize === 'function') {
                    return this.duration.humanize();
                }
                if(this.duration) {
                    return this.duration;
                }
                return '';
            }

        }
    }
</script>

<style scoped>

</style>