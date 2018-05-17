<template>

    <div class="form-group">
        <label class="form-label" v-if="label">{{ label }}
            <span class="form-required" v-if="required || required === ''">*</span>
        </label>
        <input v-bind:class="{'is-invalid': invalid }" class="form-control"
               :type="type || 'text' " :id="name" :name="name" :value="value"
                v-on:input="$emit('input', $event.target.value)" :placeholder="placeholder"
               v-on="inputEvents" :required="required" ref="input"
               @focus="$emit('focus', $event)" @blur="$emit('blur', $event)">

        <div class="invalid-feedback" v-if="typeof invalid === 'string'">{{ invalid }}</div>

    </div>

</template>

<script>
    export default {
        name: "f-simple-input",
        props: {
            label:String,
            type:String,
            name:String,
            value:String,
            invalid:null,
            placeholder:String,
            required:null,
            inputEvents:{
                type:Object,
                default:function() {
                    return {};
                }
            }
        },
    }
</script>

<style scoped>

</style>