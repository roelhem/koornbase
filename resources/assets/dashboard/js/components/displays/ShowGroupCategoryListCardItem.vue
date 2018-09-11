<template>
    <b-list-group-item :class="listItemClass"
                       @click="toggleActive"
                       style="cursor:pointer;user-select:none"
                       @mouseover="e => $emit('mouseover', e)"
                       @mouseleave="e => $emit('mouseleave', e)"
    >
        <base-stamp :default-style="defaultStyle" size="md" :inverted="active" />
        <div class="flex-column w-100 pl-3">
            <div class="d-flex justify-content-between">
                <h4 :class="{'font-weight-bold':active}">{{ category.name }}</h4>
                <small class="ml-auto" :class="{'font-weight-bold':active}">( {{ category.groups.total }} )</small>
            </div>
            <div v-if="active" class="small" :class="{'font-weight-bold':active}">{{ category.description }}</div>
        </div>
    </b-list-group-item>
</template>

<script>
    import BaseStamp from "./BaseStamp";
    import useDefaultStyle from "../../mixins/useDefaultStyle";



    export default {
        components: {BaseStamp},
        name: "show-group-category-list-card-item",

        mixins:[useDefaultStyle],

        props: {
            category:{
                type:Object,
                required:true,
            },

            highlighted:{
                type:Boolean,
                default:false,
            },

            active:{
                type:Boolean,
                default:false,
            }
        },

        computed: {

            listItemClass() {
                let res = ['d-flex','pl-2'];
                let color = this.getStyle('color');

                if(color) {
                    if (this.active) {
                        res.push(`bg-${color}`);
                        res.push(`text-white`);
                    } else if (this.highlighted) {
                        res.push(`bg-${color}-lightest`);
                    }
                }
                return res;
            },
        },

        methods: {

            toggleActive() {
                if(this.active) {
                    this.$emit('deactivate', this.category.id);
                } else {
                    this.$emit('activate', this.category.id);
                }
            }

        }
    }
</script>

<style scoped>

</style>