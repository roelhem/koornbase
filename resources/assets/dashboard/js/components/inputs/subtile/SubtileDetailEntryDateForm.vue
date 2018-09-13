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

                    <v-date-picker v-model="inputValue"
                                   :mode="mode"
                                   :min-date="minDate"
                                   :max-date="maxDate"
                    >
                        <span slot-scope="sc" class="input-group">

                                <date-picker-input v-bind="sc" class="flex-grow-1" />

                            <span class="input-group-append">
                                <subtile-form-button icon="save" color="green" @click="submitForm" />
                                <subtile-form-button icon="x" color="red" @click="cancelForm" />
                            </span>
                        </span>
                    </v-date-picker>

                </td>
            </template>

        </template>

    </detail-entry>

</template>

<script>
    import moment from "moment";
    import subtileFormMixin from "../../../mixins/subtileFormMixin";
    import SubtileFormButton from "./SubtileFormButton";
    import DetailEntry from "../../layouts/cards/DetailEntry";
    import DatePickerInput from "../DatePickerInput";

    export default {
        name: "subtile-detail-entry-date-form",



        components: {
            DatePickerInput,
            SubtileFormButton,
            DetailEntry
        },

        mixins:[subtileFormMixin],


        props: {
            placeholder:String,

            outputFormat:{
                type:String,
                default:"date",
                validate(val) {
                    return ['date','datetime','object','moment'].indexOf(val) !== -1;
                }
            },

            // props passed to the v-date-picker component
            mode:{
                type:String,
                default:"single"
            },
            minDate:Date,
            maxDate:Date,

            // props passed to detail entry component
            label:String,
            icon:[String,Object],
            iconFrom:[String,Array],
            title:null,
        },

        computed: {
            dateValue() {
                if(this.value) {
                    const res = moment(this.value);
                    if(res.isValid()) {
                        return res.toDate();
                    }
                }
                return null;
            },

            formattedInputValue() {
                if(this.inputValue) {
                    const res = moment(this.inputValue);
                    if(res.isValid()) {
                        switch(this.outputFormat) {
                            case 'moment': return res;
                            case 'object': return res.toDate();
                            case 'date': return res.format('YYYY-MM-DD');
                            case 'datetime': return res.format('YYYY-MM-DD HH:mm:ss');
                        }
                    }
                }
                return null;
            }
        },

        methods: {

            reloadValue() {
                this.inputValue = this.dateValue;
            },

            submitForm() {
                this.$emit('submit', this.formattedInputValue);
                this.formActive = false;
            }

        }
    }
</script>

<style scoped>

</style>