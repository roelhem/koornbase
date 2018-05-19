<template>

    <div class="card" :class="{'card-collapsed':isCollapsed}">
        <div class="card-header" @click.prevent="isCollapsed = !isCollapsed">
            <h3 class="card-title">{{ title }}</h3>

            <div class="card-options">
                <a href="javascript:void(0);" class="card-options-collapse">
                    <i class="fe fe-chevron-up"></i>
                </a>
            </div>
        </div>


        <slot></slot>
    </div>

</template>

<script>
    export default {
        name: "card-flexible",
        props: {
            'title':String,
            'collapsed':Boolean
        },
        data: function() {
            return {
                isCollapsed: this.collapsed
            };
        }
    }
</script>

<style scoped>

</style>