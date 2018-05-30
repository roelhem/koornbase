<template>
    <tree-select v-model="value"
                 :options="options"
                 :load-options="loadOptions"
                 :multiple="multiple"
                 loadingText="Bezig met laden..."
                 clearAllText="Deselecteer alles"
                 clearValueText="Deselecteren"
                 :placeholder="placeholder || defaultPlaceholder">


        <label slot="option-label"
               slot-scope="{node, shouldShowCount, count, labelClassName, countClassName }"
               :class="labelClassName">
                {{ node.raw.title }}
            <span v-if="shouldShowCount" :class="countClassName">
                ({{ count }})
            </span>
            <span v-if="node.raw.description" class="text-muted small font-italic ml-2">
                {{ node.raw.description }}
            </span>
        </label>


    </tree-select>
</template>

<script>
    import TreeSelect from '@riophae/vue-treeselect';
    import axios from 'axios';
    import '@riophae/vue-treeselect/dist/vue-treeselect.css';

    export default {
        name: "form-model-select-tree",
        components:{TreeSelect},

        props: {
            name:String,
            placeholder:String,
            multiple:{
                type:Boolean,
                default:false,
            },

            model:{
                type:String,
                required:true,
                validator: function(val) {
                    return ['group-category','group'].indexOf(val) !== -1;
                }
            }
        },

        data: function() {
            return {
                value:null,
                options:null
            }
        },

        computed: {

            optionsSource: function () {
                switch (this.model) {
                    case 'group-category':
                        return '/select/group-categories';
                    case 'group':
                        return '/select/groups';
                    default:
                        return null;
                }
            },

            defaultPlaceholder: function () {
                switch (this.model) {
                    case 'group-category':
                        if(this.multiple) {
                            return 'Selecteer een of meerdere groep-categoriën...';
                        } else {
                            return 'Selecteer een groep-categorie...';
                        };
                    case 'group':
                        if(this.multiple) {
                            return 'Selecteer een of meerdere groepen...';
                        } else {
                            return 'Selecteer een groep...';
                        };
                    default:
                        return 'Selecteer uit de database...';
                }
            },
        },

        methods: {
            loadOptions: function({action, callback }) {

                console.log('form-select-load action', action);

                if(action === 'LOAD_ROOT_OPTIONS') {
                    axios.get(this.optionsSource).then(response => {
                        this.options = response.data.data;
                        callback();
                    }).catch(error => {
                        console.log(error);
                        callback(error);
                    });
                }

            },
        }
    }
</script>

<style scoped>

</style>