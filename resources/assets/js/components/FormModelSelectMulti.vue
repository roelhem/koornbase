<template>
    <multiselect :id="id"
                 :options="options"
                 :multiple="multiple"
                 :loading="isLoading"
                 placeholder="Zoeken..."
                 track-by="id"
                 :showLabels="true"
                 :selectLabel="labelText.select"
                 :selectGroupLabel="labelText.selectGroup"
                 :selectedLabel="labelText.selected"
                 :selectedGroupLabel="labelText.selectedGroup"
                 :deselectLabel="labelText.deselect"
                 :deselectGroupLabel="labelText.deselectGroup"
                 v-model="storedSelected">

        <template slot="singleLabel" slot-scope="{ option, search }">
            <div style="width:100%; overflow:hidden; white-space:nowrap;">
                <span>{{ option.name }}</span>
                <small v-if="option.description" class="ml-2 text-muted">
                    {{ option.description }}
                </small>
            </div>
        </template>

        <template slot="tag" slot-scope="{ option, search, remove }">
            <ref-tag class="mr-2 mb-2"
                     :label="option.name_short"
                     @mousedown.prevent
                     remove-button
                     @remove-click="remove(option)"
            />
        </template>


        <template slot="option" slot-scope="{ option, search }">
            {{ option.name }}
            <small v-if="option.description" class="ml-2 option__description">
                {{ option.description }}
            </small>
        </template>

        <span class="multiselect__single multiselect__single_placeholder" slot="placeholder">
            {{ placeholder || defaultPlaceholder }}
        </span>


    </multiselect>
</template>

<script>
    import axios from 'axios';
    import Multiselect from 'vue-multiselect';
    import RefTag from "./BaseTag";

    export default {
        name: "form-model-select-multi",
        components:{
            RefTag,
            Multiselect
        },




        props: {
            id: [String, Number],
            value: [Object, Array, String, Number],
            placeholder: String,

            multiple: {
                type:Boolean,
                default:false
            },

            model: {
                type:String,
                required:true,
                validator:function(val) {
                    return [
                        'group-category',
                        'group',
                    ].indexOf(val) !== -1;
                }
            }
        },




        data: function () {
            return {
                storedSelected:[],
                options:[],
                isLoading:false,
            }
        },





        computed: {

            selected:{
                get() {
                    return this.value;
                },
                set(newValue) {
                    this.$emit('input', newValue);
                }
            },

            optionsSource: function() {
                switch (this.model) {
                    default:
                        return '/select/'+this.model;
                }
            },

            defaultPlaceholder: function() {
                switch (this.model) {
                    case 'group-category':
                        return 'Kies een categorie...';
                    case 'group':
                        return 'Kies een groep...';
                    default:
                        return 'Kiezen...';
                }
            },

            labelText: function() {
                let result = {
                    select: null,
                    selectGroup: null,
                    selected: null,
                    selectedGroup: null,
                    deselect: 'Verwijderen',
                    deselectGroup: 'Verwijder alle'
                };

                return result;
            }
        },



        methods: {

            loadOptions() {
                this.isLoading = true;
                axios.get(this.optionsSource).then(response => {
                    this.options = response.data.data;
                    this.isLoading = false;
                }).catch(error => {
                    console.log(error);

                    this.isLoading = false;
                });
            }

        },

        mounted() {
            this.loadOptions();
        }


    }
</script>

<style scoped>

    .multiselect .option__description {
        opacity: 0.5;
    }

    .multiselect .multiselect__option {
        overflow:hidden;
        color:#FF0;
    }

</style>