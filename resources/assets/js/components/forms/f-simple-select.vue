<template>

    <div class="form-group">
        <label class="form-label" v-if="label">{{ label }}
            <span class="form-required" v-if="required || required === ''">*</span>
        </label>

        <select :name="name" class="custom-select" :required="required" @change="$emit('change', $event.target.value)">
            <option value="" disabled :selected="!value" v-if="placeholder">{{ placeholder }}</option>
            <option v-for="option in options" :value="option.value" :selected="value == option.value">{{ option.text }}</option>
        </select>

        <div class="invalid-feedback" v-if="typeof invalid === 'string'">{{ invalid }}</div>
    </div>

</template>

<script>
    export default {
        name: "f-simple-select",
        props: [
            "label",
            "name",
            "options",
            "value",
            "invalid",
            "required",
            "placeholder"
        ],
        model: {
            prop: 'value',
            event: 'change'
        }
    }
</script>

<style scoped>

</style>