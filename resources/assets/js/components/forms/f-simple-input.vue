<template>

    <div class="form-group">
        <label class="form-label" v-if="label">{{ label }}
            <span class="form-required" v-if="required || required === ''">*</span>
        </label>
        <input v-bind:class="{'is-invalid': invalid }" class="form-control"
               :type="type || 'text' " :id="name" :name="name" :value="value"
                v-on:input="$emit('input', $event.target.input)" :placeholder="placeholder" :required="required">

        <div class="invalid-feedback" v-if="typeof invalid === 'string'">{{ invalid }}</div>

    </div>

</template>

<script>
    export default {
        name: "f-simple-input",
        props: [
            "label",
            "type",
            "name",
            "value",
            "invalid",
            "placeholder",
            "required"
        ],
        mounted() {
            console.log(this.required);
        }
    }
</script>

<style scoped>

</style>