<template>
    <div>
        <b-form-input name="name" placeholder="Volledige naam" />
        <b-input-group>
            <b-input-group-prepend>
                <label class="input-group-text">
                    <input type="checkbox" />
                </label>
            </b-input-group-prepend>
            <b-form-input name="name_short" placeholder="Korte naam" />
        </b-input-group>
    </div>
</template>

<script>
    export default {
        name: "form-input-name",

    }
</script>

<style scoped>

</style>