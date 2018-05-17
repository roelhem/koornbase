<template>

    <table class="table">
        <thead>
            <tr>
                <th class="w-1"></th>
                <th v-for="column in columns"
                    :class="column.headerClass">{{ column.label }}</th>
                <th class="w-1"></th>
            </tr>

        </thead>
        <draggable :value="rows" @input="orderRows" element="tbody" class="form-table-draggable" :options="{handle:'.draggable-handle'}">
            <tr v-for="(row, rowIndex) in rows" :key="rowIndex" :class="{'bg-light':secureDelete && row._deleted}">

                <input v-if="includeIds" type="hidden" :name="getCellName(rowIndex, 'id')" :value="row.id">
                <input v-if="secureDelete && row._deleted" type="hidden" :name="getCellName(rowIndex, '_deleted')" value="1">

                <td class="draggable-handle">
                    <i class="fa fa-sort"></i>
                </td>

                <td v-for="column in columns" :class="column.cellClass">
                    <component v-if="column.component"
                               :is="column.component"
                               :form-table-row="row"
                               :form-table-row-index="rowIndex"
                               :form-table-column="column"
                               v-bind="getCellProps(row, rowIndex, column)"
                               v-on="getCellEventListeners(row, rowIndex, column)"/>
                </td>

                <td>
                    <button v-if="secureDelete && row._deleted" type="button" class="btn btn-secondary btn-icon" @click="restoreRow(row, rowIndex)">
                        <i class="fa fa-repeat"></i>
                    </button>
                    <button v-else type="button" class="btn btn-outline-danger btn-icon" @click="deleteRow(row, rowIndex)">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        </draggable>

        <tfoot>
            <tr>
                <td></td>

                <td :colspan="columns.length"></td>

                <td>
                    <button type="button" class="btn btn-outline-success btn-icon" @click="createRow()">
                        <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tr>
        </tfoot>

    </table>

</template>

<script>
    import CustomControlInput from "./custom-control-input";
    import draggable from 'vuedraggable';

    console.log(draggable);

    export default {
        components: {CustomControlInput, draggable},
        name: "form-table-input",

        model: {
            prop:'rows',
            event:'change'
        },

        props: {
            columns:{
                type:Array,
                default:function() { return []; }
            },
            rows:{
                type:Array,
                default:function() { return []; },
            },
            name:{
                type:String,
            },
            includeIds:{
                type:Boolean,
                default:true
            },
            secureDelete:{
                type:Boolean,
                default:true,
            }
        },

        data: function() {
            return {}
        },

        methods: {

            getCellName: function(rowIndex, columnName) {
                if(rowIndex < 0) {
                    return this.name;
                } else {
                    return this.name + '[' + rowIndex + '][' + columnName + ']';
                }
            },

            getCellProps: function(row, rowIndex, column) {
                let props = {
                    name: this.getCellName(rowIndex, column.name)
                };

                let modelProp = 'value';
                if(column.component && column.component.model && column.component.model.prop) {
                    modelProp = column.component.model.prop;
                }
                props[modelProp] = row[column.name];

                if(this.secureDelete && row._deleted) {
                    props['disabled'] = true;
                }

                return Object.assign(props, column.props || {});
            },

            getCellEventListeners: function(row, rowIndex, column) {
                let res = {};

                let modelEvent = 'input';

                if (column.component && column.component.model && column.component.model.event) {
                    modelEvent = column.component.model.event;
                }

                res[modelEvent] = (newValue, options) => {
                    this.changeCell(row, rowIndex, column, newValue, options);
                };

                return res;
            },

            changeCell: function(row, rowIndex, column, newValue, options) {
                // Copy the value from the prompt.
                let result = this.rows.slice();

                // Set the value of this cell to the new value.
                result[rowIndex][column.name] = newValue;

                // Check if there were some additional options send as well.
                if(options) {

                    // Check if the otherValues option is set.
                    if(options.otherValues !== undefined) {

                        // Set all the values other than this column to the options.otherValues value
                        for(let i = 0; i < result.length; i++) {
                            if(i !== rowIndex) {
                                result[i][column.name] = options.otherValues;
                            }
                        }
                    }

                }

                // Emit a change event with the new rows value.
                this.$emit('change', result);
            },

            deleteRow: function(row, rowIndex) {
                let res = this.rows.slice();

                if(this.secureDelete && row.id) {
                    res[rowIndex]['_deleted'] = true;
                } else {
                    res.splice(rowIndex,1);
                }

                this.$emit('change', res);
            },

            restoreRow: function(row, rowIndex) {
                let res = this.rows.slice();

                res[rowIndex]['_deleted'] = undefined;

                this.$emit('change', res);
            },

            createRow: function() {
                let res = this.rows.slice();

                let newRow = {};
                for(let i = 0; i < this.columns.length; i++) {
                    const column = this.columns[i];
                    if(typeof column.defaultValue === 'function') {
                        newRow[column.name] = column.defaultValue();
                    } else {
                        newRow[column.name] = column.defaultValue;
                    }
                }

                res.push(newRow);

                this.$emit('change', res);

            },

            orderRows: function( newOrdering ) {

                this.$emit('change', newOrdering);

            }

        }
    }
</script>

<style scoped>

    .form-table-draggable tr td {
        position:relative;
    }

    .form-table-draggable tr.sortable-chosen td {
        background-color:#FFF;
    }

</style>