<template>

    <card-filter title="Lidstatus filter" v-model="active">

        <div class="card-body">
            <div class="custom-switches-stacked">
                <custom-control-input v-for="option in options"
                                      :key="option"
                                      type="checkbox"
                                      color="green"
                                      v-model="filterValue"
                                      :value="option"
                                      as-switch>
                    <membership-status :value="option"></membership-status>
                </custom-control-input>
            </div>
        </div>


    </card-filter>

</template>

<script>

    export default Vue.extend({
        name: "membership-status-filter",

        props: {
            value:Array,
        },

        data: function() {
            return {
                options: [0,1,2,3],
                memorized: []
            }
        },

        computed: {
            active: {
                get: function() {
                    return this.value !== null;
                },
                set: function(newValue) {
                    this.$emit('input', newValue ? this.memorized : null );
                }
            },
            filterValue: {
                get: function() {
                    if(this.active) {
                        return this.value;
                    } else {
                        return this.memorized;
                    }
                },
                set: function(newValue) {
                    this.memorized = newValue;
                    this.$emit('input', newValue);
                }
            }
        },

        components: {
            'custom-control-input':require('../../../forms/custom-control-input'),
        }
    });
</script>

<style scoped>

</style>