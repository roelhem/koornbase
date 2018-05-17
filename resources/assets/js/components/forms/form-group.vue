<template>

    <div class="form-group">

        <label class="form-label" v-if="label">
            {{ label }}

            <span class="form-required" v-if="required">*</span>

        </label>

        <slot></slot>

    </div>

</template>

<script>
    export default {
        name: "form-group",

        props: {
            label: {
                type:String
            },
            required: {
                type:Boolean,
                default:false
            }
        }
    }
</script>

<style scoped>

</style>