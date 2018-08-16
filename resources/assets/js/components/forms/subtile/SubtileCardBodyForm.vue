<template xmlns:v-contenteditable="http://www.w3.org/1999/xhtml">

    <div class="card-body subtile-card-body-form">



        <template v-if="!editing">

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

        <div v-if="editing"
             key="input"
             class="subtile-card-body-form--input"
             v-contenteditable:inputValue="editing"
        ></div>


        <div class="subtile-card-body-form--toolbar" :class="toolbarClass">


            <subtile-form-button v-if="!editing"
                                 class="subtile-card-body-form--edit-button"
                                 icon="edit-3"
                                 @click="startEdit"
            />

            <subtile-form-button v-if="editing"
                                 class="subtile-card-body-form--save-button"
                                 icon="save"
                                 @click="saveEdit"
            />

            <subtile-form-button v-if="editing"
                                 class="subtile-card-body-form--cancel-button"
                                 icon="x"
                                 @click="cancelEdit"
            />

        </div>

    </div>

</template>

<script>
    import SubtileFormButton from "./SubtileFormButton";

    export default {
        components: {SubtileFormButton},
        name: "subtile-card-body-form",

        props: {
            value:String,
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

        data() {
            return {
                inputValue:'',
                editing:false,
            }
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

        methods: {

            startEdit() {
                this.inputValue = this.value || '';
                this.editing = true;
                this.$emit('start');
            },

            cancelEdit() {
                this.editing = false;
                this.$emit('cancel');
            },

            saveEdit() {
                this.$emit('submit', this.inputValue);
                this.editing = false;
            }
        }
    }
</script>

<style scoped>

    .subtile-card-body-form--toolbar {
        position: absolute;
        right: 0;
        bottom: 0;
    }


</style>