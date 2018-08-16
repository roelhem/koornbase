<template>

    <span>
        <template v-if="!formActive">
            {{ value }} <subtile-form-button icon="edit-3" color="blue" @click="activateForm" />
        </template>
        <template v-if="formActive">

            <span class="input-group">
                <input type="text" class="form-control" :placeholder="placeholder" v-model="inputValue" />
                <span class="input-group-append">
                    <subtile-form-button icon="save" color="green" @click="submitForm" />
                    <subtile-form-button icon="x" color="red" @click="cancelForm" />
                </span>
            </span>
        </template>
    </span>

</template>

<script>
    import SubtileFormButton from "./SubtileFormButton";
    import subtileFormMixin from "../../../mixins/subtileFormMixin";
    import BaseIcon from "../../BaseIcon";

    export default {
        components: {
            BaseIcon,
            SubtileFormButton},
        name: "subtile-single-input-form",
        mixins:[subtileFormMixin],

        props: {
            placeholder:String
        }
    }
</script>

<style scoped>

</style>