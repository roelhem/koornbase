<template>

    <tabler-form-group v-bind="tablerFormGroupProps">
        <b-row>
            <b-col>
                <input :id="id"
                       class="form-control custom-range"
                       type="range"
                       :min="min"
                       :max="max"
                       :step="step"
                       v-model="modelValue"
                       title="range-slider"
                 />
            </b-col>
            <div class="col-auto">
                <input :id="counterId"
                       class="form-control w-8"
                       type="number"
                       :min="min"
                       :max="max"
                       :step="step"
                       v-model="modelValue"
                       title="number-picker"
                />
            </div>
        </b-row>
    </tabler-form-group>

</template>

<script>
    import formGroupMixin from "../../mixins/formGroupMixin";
    import TablerFormGroup from "../TablerFormGroup";

    export default {
        components: {TablerFormGroup},
        name: "form-range-input",

        props: {
            value:Number,
            min:{
                type:Number,
                default:0,
            },
            max:{
                type:Number,
                default:10,
            },
            step:{
                type:Number,
                default:1,
            }
        },

        model: {
            prop:'value',
            event:'input'
        },

        computed: {
            modelValue: {
                get() { return this.value; },
                set(newValue) {
                    if(newValue < this.min) { this.$emit('input', this.min); }
                    if(newValue > this.max) { this.$emit('input', this.max); }
                    this.$emit('input', newValue);
                }
            },

            counterId() {
                return this.id + '__counter';
            },


        },

        mixins:[formGroupMixin],
    }
</script>

<style scoped>

</style>