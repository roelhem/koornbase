<template>

    <div class="card" :class="{'card-collapsed':!isActive}">

        <div class="card-header">
            <h3 class="card-title"><i class="fe fe-filter text-muted"></i> {{ title }}</h3>
            <div class="card-options left">
                <label class="custom-switch m-0">
                    <input type="checkbox" class="custom-switch-input" v-model="isActive" @change="$emit('change', $event.target.checked)">
                    <span class="custom-switch-indicator"></span>
                </label>
            </div>

        </div>

        <slot></slot>

    </div>

</template>

<script>
    export default {
        name: "card-filter",
        props: ['title', 'active'],
        data: function() {
            return {
                'isActive': this.active
            }
        },
        model: {
            prop: 'active',
            event: 'change'
        }
    }
</script>

<style scoped>

</style>