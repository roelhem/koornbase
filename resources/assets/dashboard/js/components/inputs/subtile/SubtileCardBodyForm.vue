<template xmlns:v-contenteditable="http://www.w3.org/1999/xhtml">

    <div class="card-body subtile-card-body-form">



        <template v-if="!formActive">

            <div v-if="value"
                 key="display"
                 class="subtile-card-body-form--display"
                 :class="displayClass"
            >
                {{ value }}
            </div>
            <div v-else
                 key="placeholder"
                 class="subtile-card-body-form--display-placeholder"
                 :class="displayPlaceholderClass"
            >
                {{ displayPlaceholder }}
            </div>

        </template>

        <div v-if="formActive"
             key="input"
             class="subtile-card-body-form--input"
             v-contenteditable:inputValue="formActive"
        ></div>


        <div class="subtile-card-body-form--toolbar" :class="toolbarClass">


            <subtile-form-button v-if="!disabled && !formActive"
                                 class="subtile-card-body-form--edit-button"
                                 icon="edit-3"
                                 color="blue"
                                 @click="activateForm"
            />

            <subtile-form-button v-if="formActive"
                                 class="subtile-card-body-form--save-button"
                                 icon="save"
                                 color="green"
                                 @click="submitForm"
            />

            <subtile-form-button v-if="formActive"
                                 class="subtile-card-body-form--cancel-button"
                                 icon="x"
                                 color="red"
                                 @click="cancelForm"
            />

        </div>

    </div>

</template>

<script>
    import SubtileFormButton from "./SubtileFormButton";
    import subtileFormMixin from "../../../mixins/subtileFormMixin";

    export default {
        components: {SubtileFormButton},
        name: "subtile-card-body-form",

        mixins:[subtileFormMixin],

        props: {
            placeholder:String,

            // Class configuration
            displayClass: {
                type:[String,Array],
                default:function() {
                    return ['text-muted-dark'];
                }
            },

            displayPlaceholderClass: {
                type:[String,Array],
                default:function() {
                    return ['text-muted','font-italic','small','text-center'];
                }
            },

            toolbarClass:[String,Array]

        },

        computed: {
            // DETERMINE THE STRINGS TO DISPLAY
            displayPlaceholder() {
                return this.placeholder;
            },

            editPlaceholder() {
                return this.placeholder;
            },
        },
    }
</script>

<style scoped>

    .subtile-card-body-form--toolbar {
        position: absolute;
        right: 0;
        bottom: 0;
    }


</style>