<template>

    <detail-entry :label="label"
                  :icon="icon"
                  :icon-from="iconFrom"
                  :title="title"
    >
        <template slot="td">
            <template v-if="!formActive">
                <td>
                    <slot>{{ value }}</slot>
                </td>
                <td class="px-0 py-1" style="width: 1px">
                    <subtile-form-button v-if="!disabled" icon="edit-3" color="blue" @click="activateForm" />
                </td>
            </template>

            <template v-if="formActive">
                <td class="p-1" colspan="2">


                    <span class="input-group flex-nowrap">
                        <slot name="input" :input-value="inputValue" :input-callback="inputCallback">
                            <input type="text" class="form-control" :placeholder="placeholder" v-model="inputValue" />
                        </slot>
                        <span class="input-group-append">
                            <subtile-form-button icon="save" color="green" @click="submitForm" />
                            <subtile-form-button icon="x" color="red" @click="cancelForm" />
                        </span>
                    </span>

                </td>
            </template>

        </template>

    </detail-entry>

</template>

<script>
    import subtileFormMixin from "../../../mixins/subtileFormMixin";
    import DetailEntry from "../../layouts/cards/DetailEntry";
    import SubtileFormButton from "./SubtileFormButton";

    export default {
        components: {
            SubtileFormButton,
            DetailEntry
        },
        name: "subtile-detail-entry-form",

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
            inputCallback() {
                return (val) => {
                    this.inputValue = val;
                };
            }
        }
    }
</script>

<style scoped>

</style>