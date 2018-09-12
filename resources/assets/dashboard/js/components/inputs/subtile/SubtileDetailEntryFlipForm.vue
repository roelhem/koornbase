<template>

    <detail-entry :label="label"
                  :icon="icon"
                  :icon-from="iconFrom"
                  :title="title"
                  :flipped="flipped"
    >
        <template slot="td">
                <td>
                    <slot>{{ value }}</slot>
                </td>
                <td class="px-0 py-1" style="width: 1px">
                    <subtile-form-button icon="edit-3" color="blue" @click="activateForm" />
                </td>
        </template>

        <template slot="back">

            <h4>{{ label }} bewerken</h4>

            <div>
                <slot name="form" :input-value="inputValue" :input-callback="inputCallback">
                    <pre>{{ value }}</pre>
                </slot>
            </div>


            <div class="text-right">
                <b-button variant="secondary"
                          size="sm"
                          @click="cancelForm"
                >Annuleren</b-button>

                <b-button variant="primary"
                          size="sm"
                          @click="submitForm"
                >Opslaan</b-button>
            </div>

        </template>

    </detail-entry>

</template>

<script>
    import subtileFormMixin from "../../../mixins/subtileFormMixin";
    import DetailEntry from "../../layouts/cards/DetailEntry";
    import SubtileFormButton from "./SubtileFormButton";
    import BaseIcon from "../../displays/BaseIcon";

    export default {
        name: "subtile-detail-entry-flip-form",

        components: {
            BaseIcon,
            SubtileFormButton,
            DetailEntry
        },

        mixins:[subtileFormMixin],

        props: {
            placeholder:String,

            // props passed to detail entry component
            label:String,
            icon:[String,Object],
            iconFrom:[String,Array],
            title:null,
        },

        computed: {
            flipped() {
                return this.formActive;
            },

            inputCallback() {
                return (newValue) => {
                    this.inputValue = newValue;
                }
            }
        }
    }
</script>

<style scoped>

</style>