<template>

    <div class="card-body">
        <div class="custom-controls-stacked">

            <f-custom-checkbox v-for="state in states" :key="state" :checked="hasVal(state)" @change="include => setVal(state, include)">
                <membership-status :value="state"></membership-status>
            </f-custom-checkbox>

        </div>
    </div>

</template>

<script>
    import SearchFilter from "../../../../vue-extensions/SearchFilter";

    export default SearchFilter.extend({
        name: "people-search-filter-membership-status",
        data: function() {
            return {
                states: [0,1,2,3]
            }
        },
    });
</script>

<style scoped>

</style>