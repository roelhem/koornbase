<template>

    <div class="group-filter-groups">
        <ul>
            <li v-for="cat in cats">
                <label class="group-category-header p-1">{{ cat.name_short }}</label>
                <ul v-if="cat.groups">
                    <li v-for="group in cat.groups">
                        <label class="group-header custom-control custom-checkbox mx-1">
                            <input type="checkbox" class="custom-control-input" :value="group.slug" v-model="val">
                            <span class="custom-control-label">{{ group.name_short }}</span>
                        </label>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

</template>

<script>
    import axios from 'axios';

    export default {
        name: "people-group-filter",
        props: {
            params:Object,
            value:Array,
            source:{
                type:String,
                default:'/people/search/groups'
            }
        },
        data: function() {
            return {
                cats:{},
                isLoading: true,
                val:this.value
            };
        },
        methods: {
            reload: function() {
                this.isLoading = true;
                axios.get(this.source, {params: this.params}).then(response => {
                    this.cats = response.data.data;

                    this.isLoading = false;
                }).catch(error => {
                    console.log(error);
                    this.isLoading = false;
                });
            }
        },
        watch: {
            val: function(val) {
                console.log(val);
                this.$emit('input', val);
            }
        },
        created() {
            console.log('created');
            this.reload();
        },
    }
</script>

<style scoped>

    .group-filter-groups {
        max-height: 300px;
        overflow-y: auto;
    }

</style>