<template>

    <div class="card" :class="{'card-collapsed':!checked}">

        <div class="card-status bg-info"></div>

        <div class="card-header">
            <h3 class="card-title"><i class="fe fe-filter text-muted"></i> {{ title }}</h3>
            <div class="card-options left">
                <custom-control-input type="checkbox" as-switch v-model="checked" />
            </div>

        </div>

        <slot></slot>

    </div>

</template>

<script>
    export default {
        name: "card-filter",
        props: ['title', 'active'],
        computed: {
            checked:{
                get:function() {
                    return this.active;
                },
                set:function(newValue) {
                    this.$emit('change', newValue );
                }
            }
        },
        model: {
            prop: 'active',
            event: 'change'
        },
        components: {
            'custom-control-input':require('../forms/custom-control-input')
        }
    }
</script>

<style scoped>

</style>